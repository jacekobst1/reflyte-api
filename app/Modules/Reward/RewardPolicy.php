<?php

declare(strict_types=1);

namespace App\Modules\Reward;

use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\User\User;

final class RewardPolicy
{
    public const string VIEW = 'view';
    public const string CREATE = 'create';
    public const string UPDATE = 'update';
    public const string DELETE = 'delete';

    public function view(User $user, Reward $reward): bool
    {
        return true;
    }

    public function create(User $user, ReferralProgram $referralProgram): bool
    {
        return $user->team_id->equals(
            $referralProgram->getTeamId()
        );
    }

    public function update(User $user, Reward $reward): bool
    {
        return $user->team_id->equals(
            $reward->getTeamId()
        );
    }

    public function delete(User $user, Reward $reward): bool
    {
        return true;
    }
}
