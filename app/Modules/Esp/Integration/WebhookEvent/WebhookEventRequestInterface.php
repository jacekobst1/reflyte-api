<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\WebhookEvent;

use App\Modules\Esp\Dto\EspSubscriberStatus;

interface WebhookEventRequestInterface
{
    public function getId(): string;

    public function getEmail(): string;

    public function getStatus(): EspSubscriberStatus;
}
