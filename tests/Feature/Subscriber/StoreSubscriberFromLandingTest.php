<?php

declare(strict_types=1);

namespace Feature\Subscriber;

use App\Modules\Subscriber\Subscriber;
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
            'referer_subscriber_id' => $existingSubscriber->id,
            'status' => SubscriberStatus::Received,
        ]);
        $this->assertCount(1, $existingSubscriber->referrals);
    }
}
