<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Auth\Enums\RoleEnum;
use App\Modules\Newsletter\Newsletter;
use App\Modules\ReferralProgram\ReferralProgram;
use App\Modules\Reward\Reward;
use App\Modules\Team\Team;
use App\Modules\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

final class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $testPassword = Hash::make(Config::get('env.test_user_password'));

        // Admin
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => $testPassword,
        ]);
        $admin->assignRole(RoleEnum::Admin);

        // User
        $user = User::factory()->create([
            'email' => 'user@test.com',
            'password' => $testPassword,
        ]);
        $user->assignRole(RoleEnum::User);

        // User with everything
        $completeUser = User::factory()->create([
            'email' => 'complete-user@test.com',
            'password' => $testPassword,
        ]);
        $completeUser->assignRole(RoleEnum::User);
        $team = $this->createTeam($completeUser);
        $newsletter = $this->createNewsletter($team);
        $referralProgram = $this->createReferralProgram($newsletter);
        $this->createReward($referralProgram);
    }

    private function createTeam(User $user): Team
    {
        $team = Team::factory()->create([
            'owner_user_id' => $user->id,
        ]);

        $user->team()->associate($team);
        $user->save();

        return $team;
    }

    private function createNewsletter(Team $team): Newsletter
    {
        return Newsletter::factory()->for($team)->create();
    }

    private function createReferralProgram(Newsletter $newsletter): ReferralProgram
    {
        return ReferralProgram::factory()->for($newsletter)->create();
    }

    private function createReward(ReferralProgram $referralProgram): Reward
    {
        return Reward::factory()->for($referralProgram, 'rewardable')->create();
    }
}
