<?php

declare(strict_types=1);

namespace App\Modules\Reward;

use App\Http\Controllers\Controller;
use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Reward\Requests\CreateRewardRequest;
use App\Modules\Reward\Services\Http\RewardCreator;
use App\Shared\Response\JsonResp;
use Illuminate\Http\JsonResponse;

class RewardController extends Controller
{
    public function storeProgramReward(
        ReferralProgram $program,
        CreateRewardRequest $data,
        RewardCreator $rewardCreator,
    ): JsonResponse {
        $reward = $rewardCreator->createProgramReward($program, $data);

        return JsonResp::created(['id' => $reward->id]);
    }
}
