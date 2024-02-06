<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Services\Internal;

use App\Modules\Reward\Services\Internal\RewardGranter;
use App\Modules\Subscriber\Subscriber;

final readonly class RefererRewarder
{
    public function __construct(
        private RewardGranter $rewardGranter,
    ) {
    }

    public function handle(Subscriber $subscriberFromWebhook): void
    {
        $referer = $subscriberFromWebhook->referer;

        if (!$referer || !$referer->isActive() || !$subscriberFromWebhook->isActive()) {
            return;
        }

        $this->updateRefererRefCount($referer);
        $this->grantRewardToReferer($referer);
    }

    private function updateRefererRefCount(?Subscriber $referer): void
    {
        $referer->ref_count = $referer->referrals()->count();
        $referer->update();
    }

    private function grantRewardToReferer(?Subscriber $referer): void
    {
        $referralProgramIsActive = $referer?->getReferralprogram()?->active;

        if ($referralProgramIsActive) {
            $this->rewardGranter->grantRewardIfPointsAchieved($referer);
        }
    }
}
