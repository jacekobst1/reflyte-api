<?php

declare(strict_types=1);

use App\Mail\RewardGrantedMail;
use App\Modules\Reward\Reward;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::get('/join/{refCode}', [SubscriberController::class, 'redirectByRefCode'])->whereAlphaNumeric('refCode');
Route::get('/test-mail-view', function () {
//    Subscriber::factory()->create();

    $subscriber = Subscriber::first();
    $reward = Reward::first();
    return new RewardGrantedMail($subscriber, $reward);
});
