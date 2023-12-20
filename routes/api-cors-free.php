<?php

declare(strict_types=1);

use App\Modules\Subscriber\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::prefix('/subscribers')->group(function () {
    Route::post('/from-landing', [SubscriberController::class, 'postSubscriberFromLanding']);
});

