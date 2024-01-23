<?php

declare(strict_types=1);

namespace App\Modules\Subscriber;

use App\Exceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Modules\Subscriber\Requests\CreateSubscriberRequest;
use App\Modules\Subscriber\Resources\SubscriberResource;
use App\Modules\Subscriber\Services\Http\SubscriberFromLandingCreator;
use App\Modules\Subscriber\Services\Http\SubscriberGetter;
use App\Modules\Subscriber\Services\Http\SubscriberWebhookHandler;
use App\Shared\Response\JsonResp;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Ramsey\Uuid\UuidInterface;

class SubscriberController extends Controller
{
    public function getUserSubsribers(SubscriberGetter $subscriberGetter): AnonymousResourceCollection
    {
        $subscribers = $subscriberGetter->paginateByLoggedUser();

        return SubscriberResource::collection($subscribers);
    }

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
    public function postSubscriberFromLanding(
        CreateSubscriberRequest $data,
        SubscriberFromLandingCreator $creator,
    ): JsonResponse {
        $subscriber = $creator->create($data);

        return JsonResp::created(['id' => $subscriber->id]);
    }

    public function postWebhookEvent(
        UuidInterface $newsletterId,
        Request $request,
        SubscriberWebhookHandler $updater,
    ): JsonResponse {
        $updater->updateOrCreate($newsletterId, $request->input());

        return JsonResp::success();
    }
}
