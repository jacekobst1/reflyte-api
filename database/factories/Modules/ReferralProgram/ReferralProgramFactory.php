<?php

declare(strict_types=1);

namespace Database\Factories\Modules\ReferralProgram;

use App\Modules\Newsletter\Newsletter;
use App\Modules\ReferralProgram\ReferralProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReferralProgram>
 */
class ReferralProgramFactory extends Factory
{
    protected $model = ReferralProgram::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'newsletter_id' => Newsletter::factory(),
            'active' => true,
        ];
    }
}
