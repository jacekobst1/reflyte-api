<?php

declare(strict_types=1);

namespace App\Modules\Esp\Services;

use App\Modules\Esp\Integration\EspClientInterface;
use App\Modules\Subscriber\Subscriber;

final class EspSubscriberUpdater
{
    public function __construct(private readonly EspClientInterface $espClient)
    {
    }

    public function fillFields(string $id, Subscriber $subscriber): void
    {
        $this->espClient->updateSubscriber($id, [
            'fields' => [
                'rlt_ref_code' => $subscriber->ref_code,
                'rlt_ref_link' => $subscriber->ref_link,
                'rlt_is_ref' => $subscriber->is_ref,
                'rlt_ref_count' => $subscriber->ref_count,
            ]
        ]);
    }
}
