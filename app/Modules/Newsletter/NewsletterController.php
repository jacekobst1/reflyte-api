<?php

declare(strict_types=1);

namespace App\Modules\Newsletter;

use App\Exceptions\ConflictException;
use App\Http\Controllers\Controller;
use App\Modules\Newsletter\Requests\CreateNewsletterRequest;
use App\Modules\Newsletter\Services\NewsletterCreator;
use App\Shared\Response\JsonResp;
use Illuminate\Http\JsonResponse;
use Throwable;

class NewsletterController extends Controller
{
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
