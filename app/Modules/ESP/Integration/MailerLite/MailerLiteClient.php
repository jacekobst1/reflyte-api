<?php

declare(strict_types=1);

namespace App\Modules\ESP\Integration\MailerLite;

use App\Modules\ESP\Dto\SubscriberDto;
use App\Modules\ESP\Integration\ClientInterface;
use App\Modules\ESP\Integration\MailerLite\Dto\ResponseDto;
use App\Modules\ESP\Integration\MakeRequestTrait;
use Spatie\LaravelData\DataCollection;

final class MailerLiteClient implements ClientInterface
{
    use MakeRequestTrait;

    private string $baseUrl = 'https://connect.mailerlite.com/api';

    public function __construct(protected readonly string $apiKey)
    {
    }

    public function apiKeyIsValid(): bool
    {
        $response = $this->makeRequest()->get('groups?page=1000');

        return $response->successful();
    }

    /**
     * TODO return subscribers in batches
     * @return DataCollection<array-key, SubscriberDto>
     */
    public function getAllSubscribers(): DataCollection
    {
        $subscribers = [];
        $url = 'subscribers';

        while ($url) {
            $response = ResponseDto::from(
                $this->makeRequest()->get($url)->json()
            );

            foreach ($response->data as $item) {
                $subscribers[] = $item;
            }

            $url = $response->links->next;
        }

        return SubscriberDto::collection($subscribers);
    }
}
