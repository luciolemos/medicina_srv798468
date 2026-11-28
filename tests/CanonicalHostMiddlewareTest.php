<?php

declare(strict_types=1);

namespace Tests;

use App\Middleware\CanonicalHostMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class CanonicalHostMiddlewareTest extends TestCase
{
    private function dispatch(string $method, string $url, string $base = '/psicologia'): ResponseInterface
    {
        $middleware = new CanonicalHostMiddleware(
            new ResponseFactory(),
            'https://psicologia.natalcode.com.br',
            'psicologia.natalcode.com.br',
            $base,
        );

        $handler = new class implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return (new ResponseFactory())->createResponse(200);
            }
        };

        return $middleware->process((new ServerRequestFactory())->createServerRequest($method, $url), $handler);
    }

    public function testFolderOnMainDomainRedirectsToSubdomainKeepingPathAndQuery(): void
    {
        $response = $this->dispatch('GET', 'https://natalcode.com.br/psicologia/privacidade?x=1');

        self::assertSame(301, $response->getStatusCode());
        self::assertSame('https://psicologia.natalcode.com.br/privacidade?x=1', $response->getHeaderLine('Location'));
    }

    public function testFolderRootRedirectsToSubdomainRoot(): void
    {
        self::assertSame('https://psicologia.natalcode.com.br/', $this->dispatch('GET', 'https://natalcode.com.br/psicologia/')->getHeaderLine('Location'));
        self::assertSame('https://psicologia.natalcode.com.br/', $this->dispatch('HEAD', 'https://natalcode.com.br/psicologia')->getHeaderLine('Location'));
    }

    public function testCanonicalHostIsServedNormally(): void
    {
        self::assertSame(200, $this->dispatch('GET', 'https://psicologia.natalcode.com.br/', '')->getStatusCode());
        self::assertSame(200, $this->dispatch('GET', 'https://PSICOLOGIA.natalcode.com.br/', '')->getStatusCode());
    }

    public function testPostIsNeverRedirected(): void
    {
        self::assertSame(200, $this->dispatch('POST', 'https://natalcode.com.br/psicologia/contato')->getStatusCode());
    }
}
