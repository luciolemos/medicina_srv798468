<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Redireciona (301) para o domínio de APP_CANONICAL_URL quando a página é aberta por outro.
 *
 * Na Hostinger, a pasta de um subdomínio fica dentro do public_html do domínio principal:
 * psicologia.natalcode.com.br também respondia em natalcode.com.br/psicologia/ (conteúdo
 * duplicado). Só GET/HEAD: um POST redirecionado viraria GET e perderia o formulário.
 */
final class CanonicalHostMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly string $canonicalOrigin,
        private readonly string $canonicalHost,
        private readonly string $basePath,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();
        $host = strtolower($uri->getHost() . ($uri->getPort() !== null ? ':' . $uri->getPort() : ''));

        if (
            $this->canonicalHost === ''
            || $host === ''
            || $host === $this->canonicalHost
            || !in_array($request->getMethod(), ['GET', 'HEAD'], true)
        ) {
            return $handler->handle($request);
        }

        // Caminho dentro do app (sem a pasta em que ele foi aberto, ex.: /psicologia).
        $path = $uri->getPath();
        $base = rtrim($this->basePath, '/');
        if ($base !== '' && ($path === $base || str_starts_with($path, $base . '/'))) {
            $path = substr($path, strlen($base));
        }
        $path = '/' . ltrim($path, '/');

        $location = rtrim($this->canonicalOrigin, '/') . $path . ($uri->getQuery() !== '' ? '?' . $uri->getQuery() : '');

        return $this->responseFactory->createResponse(301)->withHeader('Location', $location);
    }
}
