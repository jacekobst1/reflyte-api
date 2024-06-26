<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\ConvertKit;

use App\Modules\Esp\Dto\EspFieldDto;
use App\Modules\Esp\Dto\EspSubscriberDto;
use App\Modules\Esp\Integration\Clients\AuthType;
use App\Modules\Esp\Integration\Clients\ConvertKit\Dto\CKSubscribersResponseDto;
use App\Modules\Esp\Integration\Clients\EspClientInterface;
use App\Modules\Esp\Integration\Clients\MakeRequestTrait;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Config;
use Ramsey\Uuid\UuidInterface;
use Spatie\LaravelData\DataCollection;

class ConvertKitClient implements EspClientInterface
{
    use MakeRequestTrait;

    private const string API_URL = 'https://api.convertkit.com/v3';
    private const int MAX_REQUESTS_PER_MINUTE = 120;

    public function __construct(private readonly string $apiKey)
    {
    }

    private function getApiKey(): string
    {
        return $this->apiKey;
    }

    private function getApiUrl(): string
    {
        return self::API_URL;
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

        return ($secondsInMinute / self::MAX_REQUESTS_PER_MINUTE) * 1.1;
    }

    public function apiKeyIsValid(): bool
    {
        $response = $this->makeRequest()->get('account');

        return $response->successful();
    }

    /**
     * @throws RequestException
     */
    public function getSubscribersTotalNumber(): int
    {
        $response = $this->makeRequest()->get('subscribers')->throw();

        return $response->json()['total_subscribers'];
    }

    /**
     * Hard limit of 50 subscribers per page.
     * @throws RequestException
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

        $response = $this->makeRequest()->withQueryParameters($queryParams)->get('subscribers')->throw()->json();
        $responseDto = CKSubscribersResponseDto::from($response);

        $subscribers = EspSubscriberDto::collection(
            array_map(
                fn($subscriber) => [
                    'id' => $subscriber['id'],
                    'email' => $subscriber['email_address'],
                    'status' => ConvertKitSubscriberStatusTranslator::translate($subscriber['state'])
                ],
                $responseDto->subscribers
            )
        );

        $nextBatchExists = $responseDto->page < $responseDto->total_pages;

        return [$subscribers, $nextBatchExists, $response];
    }

    /**
     * @throws RequestException
     */
    public function getSubscriber(string $id): ?EspSubscriberDto
    {
        $response = $this->makeRequest()->get("subscribers/{$id}")->throw()->json();
        $data = $response['subscriber'] ?? null;

        if (!$data) {
            return null;
        }

        return new EspSubscriberDto(
            id: (string)$data['id'],
            email: $data['email_address'],
            status: ConvertKitSubscriberStatusTranslator::translate($data['state'])
        );
    }

    /**
     * @throws RequestException
     */
    public function getAllFields(): DataCollection
    {
        $response = $this->makeRequest()->get('custom_fields')->throw()->json();

        return EspFieldDto::collection($response['custom_fields']);
    }

    /**
     * @throws RequestException
     */
    public function createField(string $key, string $type): bool
    {
        $response = $this->makeRequest()->post('custom_fields', [
            'label' => $key,
        ])->throw();

        return $response->created();
    }

    /**
     * @throws Exception
     */
    public function updateSubscriberFields(string $id, array $fields): bool
    {
        $response = $this->makeRequest()->put("subscribers/{$id}", [
            'fields' => $fields
        ])->throw();

        return $response->successful();
    }

    /**
     * @throws RequestException
     */
    public function createWebhook(UuidInterface $newsletterId): bool
    {
        $newsletterIdString = $newsletterId->toString();

        $events = [
            'subscriber.subscriber_activate',
            'subscriber.subscriber_unsubscribe',
        ];

        foreach ($events as $event) {
            $this->makeRequest()->post('automations/hooks', [
                'target_url' => Config::get('env.api_url') . "/esp/webhook/$newsletterIdString",
                'event' => [
                    'name' => $event,
                ],
            ])->throw();
        }

        return true;
    }
}
