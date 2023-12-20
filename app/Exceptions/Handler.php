<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Shared\Response\JsonResp;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            $message = $e->getMessage();

            if (str_contains($message, 'route')) {
                return JsonResp::routeNotFound();
            }

            if (str_contains($message, 'model')) {
                return JsonResp::resourceNotFound();
            }

            return false;
        });
    }
}
