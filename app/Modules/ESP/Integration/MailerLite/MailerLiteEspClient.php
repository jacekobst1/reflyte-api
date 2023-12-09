<?php

declare(strict_types=1);

namespace App\Modules\ESP\Integration\MailerLite;

use App\Modules\ESP\Dto\FieldDto;
use App\Modules\ESP\Dto\SubscriberDto;
use App\Modules\ESP\Integration\EspClientInterface;
use App\Modules\ESP\Integration\MailerLite\Dto\ResponseDto;
use App\Modules\ESP\Integration\MakeRequestTrait;
use Spatie\LaravelData\DataCollection;

final class MailerLiteEspClient implements EspClientInterface
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

    public function getAllFields(): DataCollection
    {
        $response = ResponseDto::from(
            $this->makeRequest()->get('fields?limit=1000')->json()
        );

        return FieldDto::collection($response->data);
    }

    public function createField(string $name, string $type): bool
    {
        $response = $this->makeRequest()->post('fields', [
            'name' => $name,
            'type' => $type,
        ]);

        return $response->created();
    }
}
