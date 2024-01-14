<?php

declare(strict_types=1);

use App\Mail\RewardGrantedMail;
use App\Modules\Auth\Enums\RoleEnum;
use App\Modules\Newsletter\NewsletterController;
use App\Modules\ReferralProgram\ReferralProgramController;
use App\Modules\Reward\Reward;
use App\Modules\Reward\RewardController;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberController;
use App\Modules\Team\TeamController;
use App\Modules\User\UserController;
use Illuminate\Support\Facades\Mail;
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
            Route::get('/', [UserController::class, 'getUsers']);
            Route::post('/', [UserController::class, 'postUser']);
        });
    });

    // User routes
    Route::group(['middleware' => ["role:$user"]], function () {
        Route::get('/team', [TeamController::class, 'getUserTeam']);
        Route::post('/teams', [TeamController::class, 'postTeam']);

        Route::get('/newsletter', [NewsletterController::class, 'getUserNewsletter']);
        Route::post('/newsletters', [NewsletterController::class, 'postNewsletter']);

        Route::get('/referral-program', [ReferralProgramController::class, 'getUserReferralProgram']);
        Route::prefix('/referral-programs')->group(function () {
            Route::post('/', [ReferralProgramController::class, 'postReferralProgram']);
            Route::post('/{id}', [ReferralProgramController::class, 'postReferralProgram']);
            Route::get('/{program}/rewards', [RewardController::class, 'getReferralProgramRewards']);
            Route::post('/{program}/rewards', [RewardController::class, 'postReferralProgramReward']);
        });

        Route::prefix('/rewards')->group(function () {
            Route::get('/{reward}', [RewardController::class, 'getReward']);
            Route::put('/{reward}', [RewardController::class, 'putReward']);
            Route::delete('/{reward}', [RewardController::class, 'deleteReward']);
        });
    });
});

/**
 * Test if this 2 routes works.
 * cors.php allowed_origins probably will block any request coming to this route.
 * Plus maybe we should add exception to VerifyCsrfToken.php
 */
Route::prefix('/subscribers')->group(function () {
    Route::post('/from-landing', [SubscriberController::class, 'postSubscriberFromLanding']);
});
Route::prefix('/esp')->group(function () {
    Route::prefix('/webhook')->group(function () {
        Route::post('/{newsletterId}', [SubscriberController::class, 'postWebhookEvent']);
    });
});

Route::get('/test-mail', function () {
    $subscriber = Subscriber::first();
    $reward = Reward::first();

    Mail::to('jacekobst1@gmail.com')->send(new RewardGrantedMail($subscriber, $reward));

    return response()->json();
});
