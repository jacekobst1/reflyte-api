<?php

declare(strict_types=1);

namespace App\Modules\Reward\Services\Internal;

use App\Mail\RewardGrantedMail;
use App\Modules\Reward\Reward;
use App\Modules\Subscriber\Subscriber;
use Illuminate\Support\Facades\Mail;

class RewardGranter
{
    public function grantRewardIfPointsAchieved(Subscriber $referer): void
    {
        $reward = $this->getMatchingReward($referer);
        $rewardAlreadyGranted = $referer->rewards()->where('reward_id', $reward?->id)->exists();

        if (!$reward || $rewardAlreadyGranted) {
            return;
        }

        $this->sendMail($referer, $reward);
        $this->attachRewardToSubscriber($referer, $reward);
    }

    private function getMatchingReward(Subscriber $referer): ?Reward
    {
        $referralProgram = $referer->getReferralprogram();
        $points = $referer->referrals()->count();

        /** @var Reward|null */
        return $referralProgram->rewards()->where('required_points', $points)->first();
    }

    private function attachRewardToSubscriber(Subscriber $referer, Reward $reward): void
    {
        $referer->rewards()->attach($reward);
    }

    private function sendMail(Subscriber $referer, Reward $reward): void
    {
        $mail = (new RewardGrantedMail($referer, $reward))->onQueue('emails');
        Mail::to($referer->email)->queue($mail);
    }
}
