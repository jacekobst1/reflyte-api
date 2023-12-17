<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\MailerLite;

use App\Modules\Esp\Dto\EspFieldDto;
use App\Modules\Esp\Dto\EspSubscriberDto;
use App\Modules\Esp\Dto\EspSubscriberStatus;
use App\Modules\Esp\Integration\EspClientInterface;
use App\Modules\Esp\Integration\MailerLite\Dto\ResponseDto;
use App\Modules\Esp\Integration\MakeRequestTrait;
use Illuminate\Support\Facades\Config;
use Ramsey\Uuid\UuidInterface;
use Spatie\LaravelData\DataCollection;

class MailerLiteEspClient implements EspClientInterface
{
    use MakeRequestTrait;

    private string $baseUrl = 'https://connect.mailerlite.com/api';
    private int $maxRequestsPerMinute = 120;

    public function __construct(private readonly string $apiKey)
    {
    }

    public function apiKeyIsValid(): bool
    {
        $response = $this->makeRequest()->get('groups?page=1000');

        return $response->successful();
    }

    public function getSubscribersTotalNumber(): int
    {
        $response = $this->makeRequest()->get('subscribers?limit=0');

        return $response->json()['total'];
    }

    public function getSubscribersBatch(?string $url = null): array
    {
        if (!$url) {
            $url = 'subscribers?limit=1000';
        }

        $response = ResponseDto::from(
            $this->makeRequest()->get($url)->json()
        );

        $data = array_map(function ($subscriber) {
            return [
                'id' => $subscriber['id'],
                'email' => $subscriber['email'],
                'status' => $subscriber['status'] === 'active' ? EspSubscriberStatus::Active : EspSubscriberStatus::Inactive,
            ];
        }, $response->data);

        $subscribers = EspSubscriberDto::collection($data);

        return [$subscribers, $response->links];
    }

    public function getAllFields(): DataCollection
    {
        $response = ResponseDto::from(
            $this->makeRequest()->get('fields?limit=1000')->json()
        );

        return EspFieldDto::collection($response->data);
    }

    public function createField(string $name, string $type): bool
    {
        $response = $this->makeRequest()->post('fields', [
            'name' => $name,
            'type' => $type,
        ]);

        return $response->created();
    }

    // TODO implement batches
    public function updateSubscriber(string $id, array $data): bool
    {
        $response = $this->makeRequest()->put("subscribers/{$id}", $data);

        return $response->successful();
    }

    public function listenForSubscriberWebhooks(UuidInterface $newsletterId): bool
    {
        $newsletterIdString = $newsletterId->toString();

        $response = $this->makeRequest()->post('webhooks', [
            'events' => [
                'subscriber.created',
                'subscriber.updated',
                'subscriber.unsubscribed',
            ],
            'url' => Config::get('env.api_url') . "/esp/webhook/$newsletterIdString",
            'enabled' => true,
        ]);

        return $response->created();
    }
}
