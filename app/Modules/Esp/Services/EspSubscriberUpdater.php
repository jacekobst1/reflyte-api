<?php

declare(strict_types=1);

namespace App\Modules\Esp\Services;

use App\Modules\Esp\Integration\EspClientInterface;
use App\Modules\Subscriber\Subscriber;
use App\Shared\RltFields;

final class EspSubscriberUpdater
{
    public function __construct(private readonly EspClientInterface $espClient)
    {
    }

    public function fillFields(string $id, Subscriber $subscriber): void
    {
        $this->espClient->updateSubscriber($id, [
            'fields' => RltFields::getSubscriberFields($subscriber),
        ]);
    }
}
