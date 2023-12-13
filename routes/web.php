<?php

declare(strict_types=1);

use App\Modules\Subscriber\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::get('/join/{refCode}', [SubscriberController::class, 'redirectByRefCode'])->whereAlphaNumeric('refCode');
