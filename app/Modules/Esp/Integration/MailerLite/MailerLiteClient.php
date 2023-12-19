<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\MailerLite;

use App\Modules\Esp\Dto\EspFieldDto;
use App\Modules\Esp\Dto\EspSubscriberDto;
use App\Modules\Esp\Dto\EspSubscriberStatus;
use App\Modules\Esp\Integration\AuthType;
use App\Modules\Esp\Integration\EspClientInterface;
use App\Modules\Esp\Integration\MailerLite\Dto\MLResponseDto;
use App\Modules\Esp\Integration\MakeRequestTrait;
use Illuminate\Support\Facades\Config;
use Ramsey\Uuid\UuidInterface;
use Spatie\LaravelData\DataCollection;

class MailerLiteClient implements EspClientInterface
{
    use MakeRequestTrait;

    private string $baseUrl = 'https://connect.mailerlite.com/api';
    private int $maxRequestsPerMinute = 120;

    public function __construct(private readonly string $apiKey)
    {
    }

    private function getAuthType(): AuthType
    {
        return AuthType::AuthorizationHeaderBearerToken;
    }

    public function getLimitOfSubscribersBatch(): int
    {
        return 1000;
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

    public function getSubscribersBatch(?array $previousResponse = null): array
    {
        $url = $previousResponse === null
            ? 'subscribers'
            : MLResponseDto::from($previousResponse)->links->next;

        $response = (array)$this->makeRequest()
            ->withQueryParameters(['limit' => $this->getLimitOfSubscribersBatch()])
            ->get($url)
            ->json();
        $responseDto = MLResponseDto::from($response);

        $subscribers = EspSubscriberDto::collection(
            array_map(
                fn($subscriber) => [
                    'id' => $subscriber['id'],
                    'email' => $subscriber['email'],
                    'status' => $subscriber['status'] === 'active' ? EspSubscriberStatus::Active : EspSubscriberStatus::Inactive,
                ],
                $responseDto->data
            )
        );

        $nextBatchExists = $responseDto->links->next !== null;

        return [$subscribers, $nextBatchExists, $response];
    }

    public function getAllFields(): DataCollection
    {
        $response = MLResponseDto::from(
            $this->makeRequest()->get('fields?limit=1000')->json()
        );

        return EspFieldDto::collection($response->data);
    }

    public function createField(string $key, string $type): bool
    {
        $response = $this->makeRequest()->post('fields', [
            'name' => $key,
            'type' => $type,
        ]);

        return $response->created();
    }

    // TODO implement batches
    public function updateSubscriberFields(string $id, array $fields): bool
    {
        $response = $this->makeRequest()->put("subscribers/{$id}", [
            'fields' => $fields
        ]);

        return $response->successful();
    }

    public function createWebhook(UuidInterface $newsletterId): bool
    {
        $newsletterIdString = $newsletterId->toString();

        $response = $this->makeRequest()->post('webhooks', [
            'url' => Config::get('env.api_url') . "/esp/webhook/$newsletterIdString",
            'enabled' => true,
            'events' => [
                'subscriber.created',
                'subscriber.updated',
                'subscriber.unsubscribed',
            ],
        ]);

        return $response->created();
    }
}
