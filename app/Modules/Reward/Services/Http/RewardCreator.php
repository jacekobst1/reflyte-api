<?php

declare(strict_types=1);

namespace App\Modules\Reward\Services\Http;

use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Reward\Requests\CreateRewardRequest;
use App\Modules\Reward\Reward;
use Illuminate\Support\Facades\Auth;

final class RewardCreator
{
    public function createProgramReward(
        ReferralProgram $program,
        CreateRewardRequest $data
    ): Reward {
        $reward = new Reward($data->toArray());
        $reward->team_id = Auth::user()->team_id;

        /** @var Reward */
        return $program->rewards()->save($reward);
    }
}
