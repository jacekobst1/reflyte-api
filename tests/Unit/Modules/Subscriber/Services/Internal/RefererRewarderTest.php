<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Subscriber\Services\Internal;

use App\Modules\Newsletter\Newsletter;
use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Reward\Services\Internal\RewardGranter;
use App\Modules\Subscriber\Services\Internal\RefererRewarder;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberStatus;
use Tests\TestCase;

class RefererRewarderTest extends TestCase
{
    public function testRefererRewarded(): void
    {
        // given
        $newsletter = Newsletter::factory()->create();

        ReferralProgram::factory()->for($newsletter)->create();
        $referer = Subscriber::factory()->for($newsletter)->create();
        $newSubscriber = Subscriber::factory()
            ->for($referer, 'referer')
            ->for($newsletter)
            ->create(['status' => SubscriberStatus::Active]);

        $rewardGranter = $this->mock(RewardGranter::class);
        $refererRewarder = new RefererRewarder($rewardGranter);

        // mock
        $rewardGranter->shouldReceive('grantRewardIfPointsAchieved')->once();

        // when
        $refererRewarder->handle($newSubscriber);

        // then
        $this->assertSame(1, $referer->referrals()->count());
    }

    public function testRefererNotRewardedIfNewSubscriberNotActive(): void
    {
        // given
        $newsletter = Newsletter::factory()->create();

        ReferralProgram::factory()->for($newsletter)->create();
        $referer = Subscriber::factory()->for($newsletter)->create();
        $newSubscriber = Subscriber::factory()
            ->for($referer, 'referer')
            ->for($newsletter)
            ->create(['status' => SubscriberStatus::Unsubscribed]);

        $rewardGranter = $this->mock(RewardGranter::class);
        $refererRewarder = new RefererRewarder($rewardGranter);

        // mock
        $rewardGranter->shouldReceive('grantRewardIfPointsAchieved')->times(0);

        // when
        $refererRewarder->handle($newSubscriber);

        // then
        $this->assertSame(1, $referer->referrals()->count());
    }
}
