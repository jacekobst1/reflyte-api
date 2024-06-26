<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\MailerLite;

use App\Modules\Esp\Dto\EspFieldDto;
use App\Modules\Esp\Dto\EspSubscriberDto;
use App\Modules\Esp\Integration\Clients\AuthType;
use App\Modules\Esp\Integration\Clients\ConvertKit\ConvertKitSubscriberStatusTranslator;
use App\Modules\Esp\Integration\Clients\EspClientInterface;
use App\Modules\Esp\Integration\Clients\MailerLite\Dto\MLResponseDto;
use App\Modules\Esp\Integration\Clients\MakeRequestTrait;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Config;
use Ramsey\Uuid\UuidInterface;
use Spatie\LaravelData\DataCollection;

class MailerLiteClient implements EspClientInterface
{
    use MakeRequestTrait;

    private const string API_URL = 'https://connect.mailerlite.com/api';
    private const int MAX_REQUESTS_PER_MINUTE = 120;

    public function __construct(private readonly string $apiKey)
    {
    }

    private function getAuthType(): AuthType
    {
        return AuthType::AuthorizationHeaderBearerToken;
    }

    private function getApiKey(): string
    {
        return $this->apiKey;
    }

    private function getApiUrl(): string
    {
        return self::API_URL;
    }

    public function getLimitOfSubscribersBatch(): int
    {
        return 1000;
    }

    public function getSafeIntervalBetweenRequests(): float
    {
        $secondsInMinute = 60;

        return ($secondsInMinute / self::MAX_REQUESTS_PER_MINUTE) * 1.1;
    }

    public function apiKeyIsValid(): bool
    {
        $response = $this->makeRequest()->get('groups?page=1000');

        return $response->successful();
    }

    /**
     * @throws RequestException
     */
    public function getSubscribersTotalNumber(): int
    {
        $response = $this->makeRequest()->get('subscribers?limit=0')->throw();

        return $response->json()['total'];
    }

    /**
     * @throws RequestException
     */
    public function getSubscribersBatch(?array $previousResponse = null): array
    {
        $url = $previousResponse === null
            ? 'subscribers'
            : MLResponseDto::from($previousResponse)->links->next;

        $response = (array)$this->makeRequest()
            ->withQueryParameters(['limit' => $this->getLimitOfSubscribersBatch()])
            ->get($url)
            ->throw()
            ->json();
        $responseDto = MLResponseDto::from($response);

        $subscribers = EspSubscriberDto::collection(
            array_map(
                fn($subscriber) => [
                    'id' => $subscriber['id'],
                    'email' => $subscriber['email'],
                    'status' => MailerLiteSubscriberStatusTranslator::translate($subscriber['status']),
                ],
                $responseDto->data
            )
        );

        $nextBatchExists = $responseDto->links->next !== null;

        return [$subscribers, $nextBatchExists, $response];
    }

    /**
     * @throws RequestException
     */
    public function getSubscriber(string $id): ?EspSubscriberDto
    {
        $response = $this->makeRequest()->get("subscribers/{$id}")->throw()->json();
        $data = $response['data'] ?? null;

        if (!$data) {
            return null;
        }

        return new EspSubscriberDto(
            id: $data['id'],
            email: $data['email'],
            status: ConvertKitSubscriberStatusTranslator::translate($data['status'])
        );
    }

    /**
     * @throws RequestException
     */
    public function getAllFields(): DataCollection
    {
        $response = MLResponseDto::from(
            $this->makeRequest()->get('fields?limit=1000')->throw()->json()
        );

        return EspFieldDto::collection($response->data);
    }

    /**
     * @throws RequestException
     */
    public function createField(string $key, string $type): bool
    {
        $response = $this->makeRequest()->post('fields', [
            'name' => $key,
            'type' => $type,
        ])->throw();

        return $response->created();
    }

    // TODO implement batches

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

        $response = $this->makeRequest()->post('webhooks', [
            'url' => Config::get('env.api_url') . "/esp/webhook/$newsletterIdString",
            'name' => 'Reflyte webhook',
            'enabled' => true,
            'events' => [
                'subscriber.created',
                'subscriber.updated',
                'subscriber.unsubscribed',
            ],
        ])->throw();

        return $response->created();
    }
}
