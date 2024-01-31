<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

final readonly class HandleCorsOwn
{
    public function __construct(private HandleCors $handleCors)
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
            Config::set('cors', Config::get('cors-subscribers-from-landing'));
        }

        return $this->handleCors->handle($request, $next);
    }
}
