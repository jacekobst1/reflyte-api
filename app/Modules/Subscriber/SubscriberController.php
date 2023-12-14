<?php

declare(strict_types=1);

namespace App\Modules\Subscriber;

use App\Exceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Modules\Subscriber\Requests\CreateSubscriberRequest;
use App\Modules\Subscriber\Requests\MailerLiteWebhookEventRequest;
use App\Modules\Subscriber\Services\Http\SubscriberFromLandingCreator;
use App\Modules\Subscriber\Services\Http\SubscriberWebhookHandler;
use App\Shared\Response\JsonResp;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Ramsey\Uuid\Uuid;
use Throwable;

class SubscriberController extends Controller
{
    public function redirectByRefCode(
        string $refCode
    ): ApplicationContract|Application|RedirectResponse|Redirector|View {
        $subscriber = Subscriber::whereRefCode($refCode)->with('newsletter')->first();

        if (!$subscriber) {
            return view('invalid-ref-code');
        }

        return redirect($subscriber->newsletter->landing_url . "?reflyteCode=$refCode");
    }

    /**
     * @throws BadRequestException
     */
    public function storeNewSubscriberFromLanding(
        CreateSubscriberRequest $data,
        SubscriberFromLandingCreator $creator,
    ): JsonResponse {
        $subscriber = $creator->create($data);

        return JsonResp::created(['id' => $subscriber->id]);
    }

    /**
     * @throws BadRequestException
     */
    public function webhookEvent(
        string $newsletterId,
        MailerLiteWebhookEventRequest $data,
        SubscriberWebhookHandler $updater,
    ): JsonResponse {
        try {
            $newsletterUuid = Uuid::fromString($newsletterId);
        } catch (Throwable) {
            throw new BadRequestException('Invalid newsletter id');
        }

        $updater->updateOrCreate($newsletterUuid, $data);

        return JsonResp::success();
    }
}
