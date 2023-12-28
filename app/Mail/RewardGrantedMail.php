<?php

declare(strict_types=1);

namespace App\Mail;

use App\Modules\Reward\Reward;
use App\Modules\Subscriber\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RewardGrantedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Subscriber $subscriber,
        public readonly Reward $reward
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Wygrana w programie polecającym newsletter ' . $this->subscriber->newsletter->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reward-granted',
            with: [
                'newsletterName' => $this->subscriber->newsletter->name,
                'rewardPoints' => $this->reward->required_points,
                'rewardName' => $this->reward->name,
                'mailText' => $this->reward->mail_text,
            ]
        );
    }

    /** @return array<int, Attachment> */
    public function attachments(): array
    {
        return [];
    }
}
