<?php

declare(strict_types=1);

namespace App\Modules\Newsletter\Services\Http;

use App\Exceptions\ConflictException;
use App\Jobs\IntegrateWithEsp\IntegrateWithEspJob;
use App\Modules\Esp\EspName;
use App\Modules\Esp\Services\EspApiKeyValidator;
use App\Modules\Esp\Services\EspFieldsCreator;
use App\Modules\Newsletter\Newsletter;
use App\Modules\Newsletter\Requests\CreateNewsletterRequest;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;
use App\Modules\ReferralProgram\Services\Internal\ReferralProgramCreator;
use App\Modules\Team\Team;
use Exception;
use Illuminate\Support\Facades\Auth;
use Throwable;

final readonly class NewsletterCreator
{
    public function __construct(
        private EspApiKeyValidator $apiKeyValidator,
        private EspFieldsCreator $espFieldsCreator,
        private ReferralProgramCreator $referralProgramCreator,
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

        $this->storeReferralProgram($newsletter);
        $this->createEspFields($newsletter->getEspConfig());
        $this->syncSubscribers($newsletter->getEspConfig());

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

    /**
     * @throws ConflictException
     */
    private function storeReferralProgram(Newsletter $newsletter): void
    {
        $this->referralProgramCreator->createReferralProgram($newsletter);
    }

    private function createEspFields(NewsletterEspConfig $espConfig): void
    {
        $this->espFieldsCreator->createFieldsIfNotExists($espConfig);
    }

    /**
     * @throws Exception
     */
    private function syncSubscribers(NewsletterEspConfig $espConfig): void
    {
        IntegrateWithEspJob::dispatch($espConfig);
    }
}
