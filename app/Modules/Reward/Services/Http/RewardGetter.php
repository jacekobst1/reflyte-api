<?php

declare(strict_types=1);

namespace App\Modules\Reward\Services\Http;

use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Reward\Reward;
use Illuminate\Database\Eloquent\Collection;

final class RewardGetter
{
    /**
     * @return Collection<array-key, Reward>
     */
    public function getByReferralProgram(ReferralProgram $referralProgram): Collection
    {
        return $referralProgram->rewards;
    }
}
