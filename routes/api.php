<?php

declare(strict_types=1);

use App\Modules\Users\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logged-user', function (Request $request) {
        return $request->user();
    });

    // Admin routes
    Route::group(['middleware' => ['role:admin']], function () {
        Route::prefix('/users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'store']);
        });
    });
});

Route::get('/test', function () {
    return response()->json([
        'message' => 'App is working!',
    ], 200);
});
