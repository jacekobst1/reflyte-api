<?php

declare(strict_types=1);

namespace App\Modules\Reward\Services\Http;

use App\Modules\Reward\Requests\UpdateRewardRequest;
use App\Modules\Reward\Reward;

final class RewardUpdater
{
    public function updateReward(
        Reward $reward,
        UpdateRewardRequest $data
    ): Reward {
        $reward->update($data->toArray());

        return $reward;
    }
}
