<?php

declare(strict_types=1);

use App\Core\Env;

require __DIR__ . '/../src/Core/Env.php';
Env::load(__DIR__ . '/../.env');

/**
 * APP_BASE configurado. No servidor embutido (`php -S`), ignora a base quando a URL pedida
 * não está sob ela — assim o mesmo .env funciona em /medico/ e na raiz durante testes locais.
 */
$configuredBase = static function (): string {
    $base = trim((string) ($_ENV['APP_BASE'] ?? ''));
    if ($base === '' || PHP_SAPI !== 'cli-server') {
        return $base;
    }

    $requestUriPath = (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
    $normalizedBase = '/' . trim($base, '/');
    if ($requestUriPath === '' || $requestUriPath === '/') {
        return '';
    }
    if ($normalizedBase !== '/' && !str_starts_with($requestUriPath, $normalizedBase . '/') && $requestUriPath !== $normalizedBase) {
        return '';
    }

    return $base;
};

if (session_status() === PHP_SESSION_NONE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
    $sessionBase = $configuredBase();
    $sessionPath = '/';
    if ($sessionBase !== '' && $sessionBase !== '/') {
        $sessionPath = '/' . trim($sessionBase, '/') . '/';
    }

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => $sessionPath,
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

$autoload = __DIR__ . '/../vendor/autoload.php';
if (!is_file($autoload)) {
    http_response_code(500);
    echo '<h1>Dependencias ausentes</h1><p>Rode <code>composer install</code> neste projeto.</p>';
    exit;
}

require $autoload;

$createApp = require __DIR__ . '/../config/app.php';
$createApp($configuredBase())->run();
