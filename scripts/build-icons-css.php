<?php

declare(strict_types=1);

/**
 * Gera public/assets/vendor/bootstrap-icons/bootstrap-icons.subset.css contendo
 * apenas os ícones usados pelo site (templates, JS e conteúdo em config/content).
 *
 * O CSS completo do Bootstrap Icons tem ~96 KiB e bloqueia a renderização;
 * o subconjunto tem poucos KB. Rode novamente sempre que um ícone novo for usado:
 *
 *   php scripts/build-icons-css.php          # gera o arquivo
 *   php scripts/build-icons-css.php --check  # falha se o arquivo estiver desatualizado
 */

$projectRoot = dirname(__DIR__);
$vendorDir = $projectRoot . '/public/assets/vendor/bootstrap-icons';
$sourceCss = $vendorDir . '/bootstrap-icons.css';
$targetCss = $vendorDir . '/bootstrap-icons.subset.css';
$checkOnly = in_array('--check', $argv, true);

/** @return list<string> */
function iconFilesIn(string $dir, string $extension): array
{
    if (!is_dir($dir)) {
        return [];
    }

    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if ($file->isFile() && str_ends_with($file->getFilename(), $extension)) {
            $files[] = $file->getPathname();
        }
    }
    sort($files);

    return $files;
}

/**
 * Coleta valores de chaves terminadas em "icon" (icon, badge_icon...) no conteúdo.
 *
 * @param array<mixed> $data
 * @param array<string, true> $icons
 */
function collectContentIcons(array $data, array &$icons): void
{
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            collectContentIcons($value, $icons);
            continue;
        }
        if (is_string($key) && str_ends_with($key, 'icon') && is_string($value) && $value !== '') {
            $icons[$value] = true;
        }
    }
}

/** @var array<string, true> $icons */
$icons = [];

// 1) Templates: classes literais (bi-xxx) e valores padrão de ícones dinâmicos (bi-{{ x|default('yyy') }}).
foreach (iconFilesIn($projectRoot . '/views', '.twig') as $file) {
    $source = (string) file_get_contents($file);
    preg_match_all('/\bbi-([a-z0-9]+(?:-[a-z0-9]+)*)/', $source, $literal);
    preg_match_all('/bi-\{\{[^}]*?default\(\s*[\'"]([a-z0-9-]+)[\'"]\s*\)/', $source, $defaults);
    preg_match_all('/[\'"]icon[\'"]\s*:\s*[\'"]([a-z0-9-]+)[\'"]/', $source, $inline);
    foreach ([...$literal[1], ...$defaults[1], ...$inline[1]] as $name) {
        $icons[$name] = true;
    }
}

// 2) JavaScript da aplicação.
foreach (iconFilesIn($projectRoot . '/public/assets/js', '.js') as $file) {
    preg_match_all('/\bbi-([a-z0-9]+(?:-[a-z0-9]+)*)/', (string) file_get_contents($file), $matches);
    foreach ($matches[1] as $name) {
        $icons[$name] = true;
    }
}

// 3) Conteúdo de todos os nichos.
foreach (iconFilesIn($projectRoot . '/config/content', '.php') as $file) {
    $content = require $file;
    if (is_array($content)) {
        collectContentIcons($content, $icons);
    }
}

$css = (string) file_get_contents($sourceCss);

if (!preg_match('/^(.*?)\n\.bi-123::before/s', $css, $headerMatch)) {
    fwrite(STDERR, "[error] formato inesperado em {$sourceCss}\n");
    exit(1);
}
$header = rtrim($headerMatch[1]);

preg_match_all('/^\.bi-([a-z0-9-]+)::before \{ content: "(\\\\[0-9a-f]+)"; \}$/m', $css, $rules, PREG_SET_ORDER);
$glyphs = [];
foreach ($rules as $rule) {
    $glyphs[$rule[1]] = $rule[2];
}

ksort($icons);
$lines = [];
$missing = [];
foreach (array_keys($icons) as $name) {
    if (isset($glyphs[$name])) {
        $lines[] = '.bi-' . $name . '::before { content: "' . $glyphs[$name] . '"; }';
    } else {
        $missing[] = $name;
    }
}

if ($missing !== []) {
    fwrite(STDERR, '[error] ícones inexistentes no Bootstrap Icons: ' . implode(', ', $missing) . "\n");
    exit(1);
}

$output = $header . "\n\n"
    . "/* Subconjunto gerado por scripts/build-icons-css.php — não edite à mão. */\n"
    . implode("\n", $lines) . "\n";

if ($checkOnly) {
    if (!is_file($targetCss) || file_get_contents($targetCss) !== $output) {
        fwrite(STDERR, "[error] {$targetCss} está desatualizado. Rode: php scripts/build-icons-css.php\n");
        exit(1);
    }
    echo '[ok  ] subconjunto de ícones atualizado (' . count($lines) . " ícones).\n";
    exit(0);
}

file_put_contents($targetCss, $output);
echo '[ok  ] ' . count($lines) . ' ícones → ' . substr($targetCss, strlen($projectRoot) + 1) . ' (' . round(strlen($output) / 1024, 1) . " KiB)\n";
