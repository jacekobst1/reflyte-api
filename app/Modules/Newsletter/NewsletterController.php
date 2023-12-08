<?php

declare(strict_types=1);

namespace App\Modules\Newsletter;

use App\Exceptions\ConflictException;
use App\Http\Controllers\Controller;
use App\Modules\Newsletter\Requests\CreateNewsletterRequest;
use App\Modules\Newsletter\Resources\NewsletterResource;
use App\Modules\Newsletter\Services\Http\NewsletterCreator;
use App\Shared\Response\JsonResp;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Throwable;

class NewsletterController extends Controller
{
    public function index(): JsonResponse
    {
        $newsletterOfUser = Auth::user()->getNewsletter();

        return JsonResp::success(
            NewsletterResource::collection([$newsletterOfUser])
        );
    }

    /**
     * @throws ConflictException
     * @throws Throwable
     */
    public function store(CreateNewsletterRequest $data, NewsletterCreator $creator): JsonResponse
    {
        $newsletter = $creator->createNewsletter($data);

        return JsonResp::success(['id' => $newsletter->id]);
    }
}
