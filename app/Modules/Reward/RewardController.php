<?php

declare(strict_types=1);

namespace App\Modules\Reward;

use App\Http\Controllers\Controller;
use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Reward\Requests\CreateRewardRequest;
use App\Modules\Reward\Requests\UpdateRewardRequest;
use App\Modules\Reward\Resources\RewardResource;
use App\Modules\Reward\Services\Http\RewardCreator;
use App\Modules\Reward\Services\Http\RewardGetter;
use App\Modules\Reward\Services\Http\RewardUpdater;
use App\Shared\Response\JsonResp;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class RewardController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function getProgramRewards(
        ReferralProgram $program,
        RewardGetter $rewardGetter
    ): AnonymousResourceCollection {
        Gate::authorize(RewardPolicy::VIEW_ANY, [Reward::class, $program]);
        
        $rewards = $rewardGetter->getByReferralProgram($program);

        return RewardResource::collection($rewards);
    }

    /**
     * @throws AuthorizationException
     */
    public function storeProgramReward(
        ReferralProgram $program,
        CreateRewardRequest $data,
        RewardCreator $rewardCreator,
    ): JsonResponse {
        Gate::authorize(RewardPolicy::CREATE, [Reward::class, $program]);

        $reward = $rewardCreator->createProgramReward($program, $data);

        return JsonResp::created(['id' => $reward->id]);
    }

    /**
     * @throws AuthorizationException
     */
    public function updateReward(
        Reward $reward,
        UpdateRewardRequest $data,
        RewardUpdater $rewardUpdater,
    ): JsonResponse {
        Gate::authorize(RewardPolicy::UPDATE, $reward);

        $rewardUpdater->updateReward($reward, $data);

        return JsonResp::success(['id' => $reward->id]);
    }
}
