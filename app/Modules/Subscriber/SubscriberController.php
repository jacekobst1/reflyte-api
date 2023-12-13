<?php

declare(strict_types=1);

namespace App\Modules\Subscriber;

use App\Http\Controllers\Controller;
use App\Shared\Response\JsonResp;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class SubscriberController extends Controller
{
    public function redirectByRefCode(
        string $refCode
    ): ApplicationContract|Application|RedirectResponse|Redirector|JsonResponse {
        $subscriber = Subscriber::whereRefCode($refCode)->with('newsletter')->first();

        if (!$subscriber) {
            return JsonResp::badRequest('Invalid ref code');
        }

        return redirect($subscriber->newsletter->landing_url);
    }
}
