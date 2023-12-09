<?php

declare(strict_types=1);

namespace App\Modules\Newsletter\Services\Http;

use App\Exceptions\ConflictException;
use App\Modules\Esp\EspName;
use App\Modules\Esp\Services\ApiKeyValidator;
use App\Modules\Newsletter\Newsletter;
use App\Modules\Newsletter\Requests\CreateNewsletterRequest;
use App\Modules\Newsletter\Services\Internal\FieldsSynchronizer;
use App\Modules\Newsletter\Services\Internal\SubscriberSynchronizer;
use App\Modules\Team\Team;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Throwable;

final class NewsletterCreator
{
    public function __construct(
        private readonly ApiKeyValidator $apiKeyValidator,
    ) {
    }

    /**
     * @throws Throwable
     * @throws ConflictException
     */
    public function createNewsletter(CreateNewsletterRequest $data): Newsletter
    {
        $team = Auth::user()->team;

        $this->checkIfTeamHasNoNewsletter($team);
        $this->validateEspApiKey($data->esp_name, $data->esp_api_key);

        $newsletter = $this->storeNewsletter($data, $team);

        $this->syncFields();
        $this->syncSubscribers();

        return $newsletter;
    }

    /**
     * @throws ConflictException
     */
    private function checkIfTeamHasNoNewsletter(Team $team): void
    {
        if ($team->newsletter()->exists()) {
            throw new ConflictException('Team already has a newsletter');
        }
    }

    /**
     * @throws ConflictException
     */
    private function validateEspApiKey(EspName $espName, string $apiKey): void
    {
        if (!$this->apiKeyValidator->apiKeyIsValid($espName, $apiKey)) {
            throw new ConflictException('Invalid ESP API key');
        }
    }

    /**
     * @throws Throwable
     */
    private function storeNewsletter(CreateNewsletterRequest $data, Team $team): Newsletter
    {
        $newsletter = new Newsletter();

        $newsletter->fill($data->toArray());
        $newsletter->team_id = $team->id;

        $newsletter->saveOrFail();

        return $newsletter;
    }

    private function syncFields(): void
    {
        /** @var FieldsSynchronizer $fieldsSynchronizer */
        $fieldsSynchronizer = App::make(FieldsSynchronizer::class);
        $fieldsSynchronizer->sync();
    }

    private function syncSubscribers(): void
    {
        /** @var SubscriberSynchronizer $subscriberSynchronizer */
        $subscriberSynchronizer = App::make(SubscriberSynchronizer::class);
        $subscriberSynchronizer->sync();
    }
}
