<?php

namespace App\Http\Middleware;

use Closure;
use Fruitcake\Cors\CorsService;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

final readonly class HandleCorsOwn
{
    public function __construct(private Container $container)
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
            $options = Config::get('cors-subscriber-from-landing');
        } else {
            $options = Config::get('cors');
        }

        $corsService = new CorsService($options);
        $handleCorsMiddleware = new HandleCors($this->container, $corsService);

        return $handleCorsMiddleware->handle($request, $next);
    }
}
