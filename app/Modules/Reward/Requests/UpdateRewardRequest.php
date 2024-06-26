<?php

declare(strict_types=1);

namespace App\Modules\Reward\Requests;

use App\Modules\Reward\Reward;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

final class UpdateRewardRequest extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $name,

        #[Nullable, StringType]
        public readonly ?string $description,

        // Validated in rules()
        public readonly int $required_points,

        #[Required, StringType]
        public readonly string $mail_text,
    ) {
    }

    public static function rules(): array
    {
        /** @var Reward $reward */
        $reward = request()->route()->parameter('reward');

        return [
            'required_points' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('rewards', 'required_points')
                    ->where('rewardable_id', $reward->rewardable_id->toString())
                    ->ignore($reward->id),
            ],
        ];
    }
}
