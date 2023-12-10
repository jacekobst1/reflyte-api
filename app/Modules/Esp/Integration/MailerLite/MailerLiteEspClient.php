<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\MailerLite;

use App\Modules\Esp\Dto\FieldDto;
use App\Modules\Esp\Dto\SubscriberDto;
use App\Modules\Esp\Integration\EspClientInterface;
use App\Modules\Esp\Integration\MailerLite\Dto\ResponseDto;
use App\Modules\Esp\Integration\MakeRequestTrait;
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

        if ($response->status() === 429) {
            dd('TOO MANY REQUESTS', $response);
        }

        return $response->successful();
    }

    /**
     * TODO return subscribers in batches
     * @return DataCollection<array-key, SubscriberDto>
     */
    public function getAllSubscribers(): DataCollection
    {
        $subscribers = [];
        $url = 'subscribers?limit=1000&filter[status]=active';

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

    public function updateSubscriber(string $id, array $data): bool
    {
        $response = $this->makeRequest()->put("subscribers/{$id}", $data);

        return $response->successful();
    }
}
