<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Reward\Services\Internal;

use App\Mail\RewardGrantedMail;
use App\Modules\Newsletter\Newsletter;
use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Reward\Reward;
use App\Modules\Reward\Services\Internal\RewardGranter;
use App\Modules\Subscriber\Subscriber;
use Illuminate\Support\Facades\Mail;
use Tests\Helpers\SanctumTrait;
use Tests\TestCase;

final class RewardGranterTest extends TestCase
{
    use SanctumTrait;

    private RewardGranter $rewardGranter;

    protected function setUp(): void
    {
        parent::setUp();

//        $this->actAsCompleteUser();
        $this->rewardGranter = new RewardGranter();
    }

    public function testGrant(): void
    {
        Mail::fake();

        // given
        $newsletter = Newsletter::factory()->create();
        ReferralProgram::factory()->for($newsletter)->create();
        $subscriber = Subscriber::factory()->for($newsletter)->create();
        $referralProgram = $subscriber->getReferralprogram();

        $reward = Reward::factory()->for($referralProgram, 'rewardable')->create([
            'required_points' => 1,
        ]);
        Subscriber::factory()->for($newsletter)->create([
            'referer_subscriber_id' => $subscriber->id,
        ]);

        // when
        $this->rewardGranter->grantRewardIfPointsAchieved($subscriber);

        // then
        $subscriber->load('rewards');
        $this->assertCount(1, $subscriber->rewards);
        $this->assertEquals($reward->id, $subscriber->rewards->first()->id);
        Mail::assertQueued(RewardGrantedMail::class, function (RewardGrantedMail $mail) use ($subscriber, $reward) {
            return $mail->subscriber->id->equals($subscriber->id) && $mail->reward->id->equals($reward->id);
        });
    }
}
