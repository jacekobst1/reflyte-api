<?php

declare(strict_types=1);

namespace App\Modules\Reward\Services\Http;

use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Reward\Requests\CreateRewardRequest;
use App\Modules\Reward\Reward;

final class RewardCreator
{
    public function createProgramReward(
        ReferralProgram $referralProgram,
        CreateRewardRequest $data
    ): Reward {
        /** @var Reward */
        return $referralProgram->rewards()->create($data->toArray());
    }
}
