<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\Clients\ActiveCampaign;

use App\Modules\Esp\Dto\EspFieldDto;
use App\Modules\Esp\Dto\EspSubscriberDto;
use App\Modules\Esp\Integration\Clients\ActiveCampaign\Dto\ACContactsResponseDto;
use App\Modules\Esp\Integration\Clients\AuthType;
use App\Modules\Esp\Integration\Clients\EspClientInterface;
use App\Modules\Esp\Integration\Clients\MakeRequestTrait;
use App\Modules\Subscriber\SubscriberStatus;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Config;
use Ramsey\Uuid\UuidInterface;
use Spatie\LaravelData\DataCollection;

// TODO dopisz testy i przetestuj na produkcji
final readonly class ActiveCampaignClient implements EspClientInterface
{
    use MakeRequestTrait;

    private const int MAX_REQUESTS_PER_MINUTE = 300;

    public function __construct(
        private string $apiKey,
        private string $apiUrl,
    ) {
    }

    private function getAuthType(): AuthType
    {
        return AuthType::AuthorizationHeaderApiToken;
    }

    private function getApiKey(): string
    {
        return $this->apiKey;
    }

    private function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    public function getLimitOfSubscribersBatch(): int
    {
        return 100;
    }

    public function getSafeIntervalBetweenRequests(): float
    {
        $secondsInMinute = 60;

        return ($secondsInMinute / self::MAX_REQUESTS_PER_MINUTE) * 1.1;
    }

    public function apiKeyIsValid(): bool
    {
        $response = $this->makeRequest()->get('users');

        return $response->successful() && is_array($response->json('users'));
    }

    /**
     * @throws RequestException
     */
    public function getSubscribersTotalNumber(): int
    {
        $response = $this->makeRequest()->get('contacts')->throw();
        $responseDto = ACContactsResponseDto::from($response->json());

        return $responseDto->meta->getTotal();
    }

    /**
     * @throws RequestException
     */
    public function getSubscribersBatch(?array $previousResponse = null): array
    {
        $queryParams = [
            'orders[cdate]' => 'desc',
            'limit' => $this->getLimitOfSubscribersBatch(),
            'offset' => 0,
            'status' => 1,
            // TODO handle also other statuses, by fetching subsribers one by one https://developers.activecampaign.com/reference/contact
        ];

        if ($previousResponse !== null) {
            $lastResponseDto = ACContactsResponseDto::from($previousResponse);
            $lastOffset = $lastResponseDto->meta->page_input->offset;
            $queryParams['offset'] = $lastOffset + $this->getLimitOfSubscribersBatch();
        }

        $response = (array)$this->makeRequest()
            ->withQueryParameters($queryParams)
            ->get('contacts')
            ->throw()
            ->json();
        $responseDto = ACContactsResponseDto::from($response);

        $subscribers = EspSubscriberDto::collection(
            array_map(
                fn($subscriber) => [
                    'id' => $subscriber['id'],
                    'email' => $subscriber['email'],
                    'status' => SubscriberStatus::Active,
                ],
                $responseDto->contacts
            )
        );

        $numberOfAlreadyFetchedSubscribers =
            $this->getLimitOfSubscribersBatch() + $responseDto->meta->page_input->offset;
        $nextBatchExists = $responseDto->meta->getTotal() > $numberOfAlreadyFetchedSubscribers;

        return [$subscribers, $nextBatchExists, $response];
    }

    /**
     * @throws RequestException
     */
    public function getSubscriber(string $id): ?EspSubscriberDto
    {
        $response = $this->makeRequest()->get("contacts/{$id}")->throw()->json();
        $contact = $response['contact'];
        $contactLists = $response['contactLists'];

        if (empty($contactLists)) {
            return null;
        }

        $contactList = $contactLists[0];

        return new EspSubscriberDto(
            id: $contact['id'],
            email: $contact['email'],
            status: ActiveCampaignSubscriberStatusTranslator::translate($contactList['status'])
        );
    }

    /**
     * @return DataCollection<array-key, EspFieldDto>
     * @throws RequestException
     */
    public function getAllFields(): DataCollection
    {
        $response = $this->makeRequest()->get('fields?limit=1000')->throw()->json();

        $data = array_map(
            fn($field) => [
                'id' => $field['id'],
                'key' => $field['title'],
            ],
            $response['fields'],
        );

        return EspFieldDto::collection($data);
    }

    /**
     * @throws RequestException
     */
    public function createField(string $key, string $type): bool
    {
        $response = $this->makeRequest()->post('fields', [
            'field' => [
                'title' => $key,
                'type' => $type,
                'visible' => 1,
            ],
        ])->throw();

        return $response->created();
    }

    /**
     * @param array<string, string> $fields
     * @throws Exception
     */
    public function updateSubscriberFields(string $id, array $fields): bool
    {
        $existsingFields = $this->getAllFields();
        $formattedFields = [];

        foreach ($existsingFields as $existsingField) {
            foreach ($fields as $key => $value) {
                if ($existsingField->key === $key) {
                    $formattedFields[] = [
                        'field' => $existsingField->id,
                        'value' => $value,
                    ];
                }
            }
        }

        $response = $this->makeRequest()->put("contacts/{$id}", [
            'contact' => [
                'fieldValues' => $formattedFields,
            ]
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
            'webhook' => [
                'name' => 'Reflyte webhook',
                'url' => Config::get('env.api_url') . "/esp/webhook/$newsletterIdString",
                'events' => [
                    'subscribe',
                ],
                'sources' => [
                    'public',
                    'admin',
                    'api',
                    'system',
                ]
            ],
        ])->throw();

        return $response->created();
    }
}
