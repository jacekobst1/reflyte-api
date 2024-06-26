<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Subscriber;

use App\Modules\Newsletter\Newsletter;
use App\Modules\Subscriber\Subscriber;
use App\Modules\Subscriber\SubscriberIsReferral;
use App\Modules\Subscriber\SubscriberStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

/**
 * @extends Factory<Subscriber>
 */
class SubscriberFactory extends Factory
{
    protected $model = Subscriber::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'newsletter_id' => Newsletter::factory(),
            'referer_subscriber_id' => null,
            'email' => fake()->email(),
            'ref_code' => strtolower(Str::random(10)),
            'ref_link' => Config::get('env.app_url') . '/join/' . Str::random(10),
            'is_referral' => SubscriberIsReferral::No,
            'ref_count' => 0,
            'status' => SubscriberStatus::Active,
        ];
    }
}
