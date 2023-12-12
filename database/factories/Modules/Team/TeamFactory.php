<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Team;

use App\Modules\Team\Team;
use App\Modules\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_user_id' => User::factory(),
            'name' => fake()->name(),
        ];
    }
}
