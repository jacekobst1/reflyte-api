<?php

declare(strict_types=1);

namespace App\Modules\Esp\Integration\MailerLite;

use App\Modules\Subscriber\SubscriberStatus;

trait WebhookEventRequestTrait
{
    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getStatus(): SubscriberStatus
    {
        return $this->statusEnum;
    }
}
