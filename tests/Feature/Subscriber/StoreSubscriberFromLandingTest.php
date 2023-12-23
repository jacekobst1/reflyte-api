<?php

declare(strict_types=1);

namespace Feature\Subscriber;

use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberIsRef;
use App\Modules\Subscriber\SubscriberStatus;
use Tests\TestCase;

final class StoreSubscriberFromLandingTest extends TestCase
{
    public function testStoreSubscriberFromLanding(): void
    {
        // given
        $existingSubscriber = Subscriber::factory()->create();
        $data = [
            'email' => 'some-random-email@gmail.com',
            'ref_code' => $existingSubscriber->ref_code,
        ];

        // when
        $response = $this->post('/api/subscribers/from-landing', $data);

        // then
        $response->assertSuccessful();
        $this->assertDatabaseHas('subscribers', [
            'email' => $data['email'],
            'newsletter_id' => $existingSubscriber->newsletter_id,
            'referer_subscriber_id' => $existingSubscriber->id,
            'status' => SubscriberStatus::Received,
            'is_ref' => SubscriberIsRef::Yes,
            'ref_count' => 0,
        ]);
        $this->assertCount(1, $existingSubscriber->referrals);
    }
}
