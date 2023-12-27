<?php

declare(strict_types=1);

namespace App\Modules\Reward\Services\Internal;

use App\Modules\Subscriber\Subscriber;

final class RewardGranter
{
    public function grantRewardIfPointsAchieved(Subscriber $subscriber): void
    {
        $referralProgram = $subscriber->getReferralprogram();
        $points = $subscriber->referrals()->count();

        $matchingReward = $referralProgram->rewards()->where('required_points', $points)->first();

        if (!$matchingReward) {
            return;
        }

        $subscriber->rewards()->attach($matchingReward, ['is_sent' => false]);
        // send email
    }
}
