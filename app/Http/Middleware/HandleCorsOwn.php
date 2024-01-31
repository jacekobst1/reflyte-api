<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class HandleCorsOwn
{
    public function __construct(private HandleCors $handleCors, private Container $container)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->path() === 'api/subscribers/from-landing') {
            config([
                'cors' => [
                    'paths' => ['api/subscribers/from-landing'],
                    'allowed_methods' => ['POST'],
                    'allowed_origins' => ['*'],
                    'allowed_origins_patterns' => [],
                    'allowed_headers' => ['*'],
                    'exposed_headers' => [],
                    'max_age' => 0,
                    'supports_credentials' => false,
                ]
            ]);
        }

        return $this->handleCors->handle($request, $next);
    }
}
