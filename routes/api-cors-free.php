<?php

declare(strict_types=1);

use App\Http\Controllers\CorsFreeController;
use App\Modules\Subscriber\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::prefix('/api')->group(function () {
    Route::prefix('/subscribers')->group(function () {
        Route::post('/from-landing', [SubscriberController::class, 'storeNewSubscriberFromLanding']);
    });
});

