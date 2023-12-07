<?php

declare(strict_types=1);

namespace App\Modules\Newsletter\Services;

use App\Exceptions\ConflictException;
use App\Modules\Newsletter\Newsletter;
use App\Modules\Newsletter\Requests\CreateNewsletterRequest;
use App\Modules\Team\Team;
use Illuminate\Support\Facades\Auth;
use Throwable;

final class NewsletterCreator
{
    /**
     * @throws Throwable
     * @throws ConflictException
     */
    public function createNewsletter(CreateNewsletterRequest $data): Newsletter
    {
        $team = Auth::user()->team;

        $this->checkIfTeamHasNoNewsletter($team);

        return $this->storeNewsletter($data, $team);
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
}
