<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberStatus;
use App\Shared\Response\JsonResp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CorsFreeController extends Controller
{
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

        $refCode = strtolower(Str::random(10));
        $refLink = 'https://join.reflyte.com/' . $refCode;
        $isRef = 'no';
        $refCount = 0;
        Subscriber::create([
            'referer_subscriber_id' => $referer->id,
            'newsletter_id' => $referer->newsletter_id,
            'email' => $email,
            'status' => SubscriberStatus::Received,
            'ref_code' => $refCode,
            'ref_link' => $refLink,
            'is_ref' => $isRef,
            'ref_count' => $refCount,
        ]);

        return JsonResp::created();
    }
}
