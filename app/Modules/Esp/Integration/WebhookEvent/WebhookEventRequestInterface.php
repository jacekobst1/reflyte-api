<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\WebhookEvent;

use App\Modules\Subscriber\SubscriberStatus;

interface WebhookEventRequestInterface
{
    public function getId(): string;

    public function getEmail(): string;

    public function getStatus(): SubscriberStatus;
}
