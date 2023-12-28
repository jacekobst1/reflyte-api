<?php

declare(strict_types=1);

namespace App\Modules\Reward\Services\Internal;

use App\Mail\RewardGrantedMail;
use App\Modules\Reward\Reward;
use App\Modules\Subscriber\Subscriber;
use Illuminate\Support\Facades\Mail;

final class RewardGranter
{
    public function grantRewardIfPointsAchieved(Subscriber $subscriber): void
    {
        $reward = $this->getMatchingReward($subscriber);

        if (!$reward) {
            return;
        }

        $this->sendMail($subscriber, $reward);
        $this->attachRewardToSubscriber($subscriber, $reward);
    }

    private function getMatchingReward(Subscriber $subscriber): ?Reward
    {
        $referralProgram = $subscriber->getReferralprogram();
        $points = $subscriber->referrals()->count();

        /** @var Reward */
        return $referralProgram->rewards()->where('required_points', $points)->first();
    }

    private function attachRewardToSubscriber(Subscriber $subscriber, Reward $reward): void
    {
        $subscriber->rewards()->attach($reward);
    }

    private function sendMail(Subscriber $subscriber, Reward $reward): void
    {
        $mail = (new RewardGrantedMail($subscriber, $reward))->onQueue('emails');
        Mail::to($subscriber->email)->queue($mail);
    }
}
