<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Newsletter;

use App\Modules\Esp\EspName;
use App\Modules\Newsletter\Newsletter;
use App\Modules\Team\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Newsletter>
 */
class NewsletterFactory extends Factory
{
    protected $model = Newsletter::class;

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
            'description' => fake()->sentence(),
            'landing_url' => fake()->url(),
            'esp_name' => EspName::MailerLite->value,
            'esp_api_key' => fake()->sha256(),
        ];
    }

    public function mailerLite(): self
    {
        return $this->state([
            'esp_name' => EspName::MailerLite->value,
        ]);
    }

    public function convertKit(): self
    {
        return $this->state([
            'esp_name' => EspName::ConvertKit->value,
        ]);
    }
}
