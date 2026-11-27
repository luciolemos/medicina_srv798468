<?php

declare(strict_types=1);

/**
 * Roteador para o servidor embutido do PHP (`php -S ... -t public scripts/php-server-router.php`).
 *
 * O `php -S` não comprime respostas, mas a produção comprime (mod_deflate/mod_brotli no
 * public/.htaccess). Sem compressão, o Lighthouse do CI mede ~3x mais bytes de CSS/HTML do
 * que o visitante recebe. Este roteador aplica gzip aos mesmos tipos que o .htaccess,
 * para o CI medir o que a produção entrega. Uso apenas local/CI — nunca em produção.
 */

$documentRoot = (string) realpath((string) $_SERVER['DOCUMENT_ROOT']);
$path = (string) (parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/');
$acceptsGzip = str_contains((string) ($_SERVER['HTTP_ACCEPT_ENCODING'] ?? ''), 'gzip');

$file = $path === '/' ? false : realpath($documentRoot . $path);
if ($file !== false && is_file($file) && str_starts_with($file, $documentRoot . DIRECTORY_SEPARATOR)) {
    $compressibleTypes = [
        'css' => 'text/css; charset=utf-8',
        'js' => 'text/javascript; charset=utf-8',
        'json' => 'application/json',
        'svg' => 'image/svg+xml',
        'txt' => 'text/plain; charset=utf-8',
        'xml' => 'application/xml',
    ];
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if (!$acceptsGzip || !isset($compressibleTypes[$extension])) {
        return false; // servidor embutido entrega o arquivo como sempre
    }

    header('Content-Type: ' . $compressibleTypes[$extension]);
    header('Content-Encoding: gzip');
    header('Vary: Accept-Encoding');
    echo gzencode((string) file_get_contents($file), 6);

    return true;
}

if ($acceptsGzip) {
    ob_start('ob_gzhandler');
}

require $documentRoot . '/index.php';
