<?php

declare(strict_types=1);

namespace App\Modules\Subscriber;

use App\Http\Controllers\Controller;
use App\Shared\Response\JsonResp;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    // TODO move method logic to separate service
    public function storeNewSubscriberFromLanding(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'ref_code' => ['required', 'string', 'alpha_num', 'lowercase', 'size:10'],
        ]);

        $email = $request->input('email');
        $refCode = $request->input('ref_code');

        $referer = Subscriber::whereRefCode($refCode)->first();

        if (!$referer) {
            return JsonResp::badRequest('Invalid ref code');
        }

        Subscriber::create([
            'referer_subscriber_id' => $referer->id,
            'newsletter_id' => $referer->newsletter_id,
            'email' => $email,
        ]);

        // TODO init reward awarding mechanism

        return JsonResp::created();
    }
}
