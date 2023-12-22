<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Reward;

use App\Modules\Reward\Reward;
use App\Modules\Team\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reward>
 */
class RewardFactory extends Factory
{
    protected $model = Reward::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'name' => fake()->name(),
            'description' => fake()->text(),
            'required_points' => rand(1, 100),
        ];
    }
}
