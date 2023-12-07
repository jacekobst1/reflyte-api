<?php

declare(strict_types=1);

use App\Modules\Auth\Enums\RoleEnum;
use App\Modules\Team\TeamController;
use App\Modules\User\UserController;
use Illuminate\Support\Facades\Route;

$admin = RoleEnum::Admin->value;
$user = RoleEnum::User->value;

Route::middleware('auth:sanctum')->group(function () use ($admin, $user) {
    // Admin and User routes
    Route::group(['middleware' => ["role:$admin|$user"]], function () {
        Route::get('/logged-user', [UserController::class, 'getLoggedUser']);
    });

    // Admin routes
    Route::group(['middleware' => ["role:$admin"]], function () {
        Route::prefix('/users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'store']);
        });
    });

    // User routes
    Route::group(['middleware' => ["role:$user"]], function () {
        Route::prefix('/teams')->group(function () {
            Route::post('/', [TeamController::class, 'store']);
        });
    });
});
