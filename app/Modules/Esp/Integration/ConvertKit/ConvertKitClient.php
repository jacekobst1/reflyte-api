<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\ConvertKit;

use App\Modules\Esp\Dto\EspFieldDto;
use App\Modules\Esp\Dto\EspSubscriberDto;
use App\Modules\Esp\Dto\EspSubscriberStatus;
use App\Modules\Esp\Integration\AuthType;
use App\Modules\Esp\Integration\ConvertKit\Dto\CKSubscribersResponseDto;
use App\Modules\Esp\Integration\EspClientInterface;
use App\Modules\Esp\Integration\MakeRequestTrait;
use Exception;
use Illuminate\Support\Facades\Config;
use Ramsey\Uuid\UuidInterface;
use Spatie\LaravelData\DataCollection;

final class ConvertKitClient implements EspClientInterface
{
    use MakeRequestTrait;

    private string $baseUrl = 'https://api.convertkit.com/v3';
    private int $maxRequestsPerMinute = 120;

    public function __construct(private readonly string $apiKey)
    {
    }

    private function getAuthType(): AuthType
    {
        return AuthType::QueryParameterApiSecret;
    }

    public function getLimitOfSubscribersBatch(): int
    {
        return 50;
    }

    public function getSafeIntervalBetweenRequests(): float
    {
        $secondsInMinute = 60;

        // TODO try on production, cause safe multiplier is 1.1
        return ($secondsInMinute / $this->maxRequestsPerMinute) * 0.1;
    }

    public function apiKeyIsValid(): bool
    {
        $response = $this->makeRequest()->get('account');

        return $response->successful();
    }

    public function getSubscribersTotalNumber(): int
    {
        $response = $this->makeRequest()->get('subscribers');

        return $response->json()['total_subscribers'];
    }

    /**
     * Hard limit of 50 subscribers per page.
     */
    public function getSubscribersBatch(?array $previousResponse = null): array
    {
        $queryParams = [
            'sort_field' => 'created_at',
            'sort_order' => 'asc',
        ];

        if ($previousResponse !== null) {
            $queryParams['page'] = CKSubscribersResponseDto::from($previousResponse)->page + 1;
        }

        $response = $this->makeRequest()->withQueryParameters($queryParams)->get('subscribers')->json();
        $responseDto = CKSubscribersResponseDto::from($response);

        $subscribers = EspSubscriberDto::collection(
            array_map(
                fn($subscriber) => [
                    'id' => $subscriber['id'],
                    'email' => $subscriber['email_address'],
                    'status' => $subscriber['state'] === 'active' ? EspSubscriberStatus::Active : EspSubscriberStatus::Inactive,
                ],
                $responseDto->subscribers
            )
        );

        $nextBatchExists = $responseDto->page < $responseDto->total_pages;

        return [$subscribers, $nextBatchExists, $response];
    }

    public function getAllFields(): DataCollection
    {
        $response = $this->makeRequest()->get('custom_fields')->json();

        return EspFieldDto::collection($response['custom_fields']);
    }

    public function createField(string $key, string $type): bool
    {
        $response = $this->makeRequest()->post('custom_fields', [
            'label' => $key,
        ]);

        return $response->created();
    }

    /**
     * @throws Exception
     */
    public function updateSubscriberFields(string $id, array $fields): bool
    {
        $response = $this->makeRequest()->put("subscribers/{$id}", [
            'fields' => $fields
        ]);

        if ($response->failed()) {
            throw new Exception($response->body());
        }

        return $response->successful();
    }

    public function createWebhook(UuidInterface $newsletterId): bool
    {
        $newsletterIdString = $newsletterId->toString();

        $events = [
            'subscriber.subscriber_activate',
            'subscriber.subscriber_unsubscribe',
        ];

        foreach ($events as $event) {
            $response = $this->makeRequest()->post('automations/hooks', [
                'target_url' => Config::get('env.api_url') . "/esp/webhook/$newsletterIdString",
                'event' => [
                    'name' => $event,
                ],
            ]);

            if (!$response->successful()) {
                return false;
            }
        }

        return true;
    }
}
