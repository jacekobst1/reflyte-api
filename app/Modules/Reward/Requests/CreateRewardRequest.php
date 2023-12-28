<?php

declare(strict_types=1);

namespace App\Modules\Reward\Requests;

use App\Modules\ReferralProgram\ReferralProgram;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

final class CreateRewardRequest extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $name,

        #[Required, StringType]
        public readonly string $description,

        // Validated in rules()
        public readonly int $required_points,

        #[Required, StringType]
        public readonly string $mail_text,
    ) {
    }

    public static function rules(): array
    {
        /** @var ReferralProgram $referralProgram */
        $referralProgram = request()->route()->parameter('program');

        return [
            'required_points' => [
                'required',
                'integer',
                Rule::unique('rewards', 'required_points')
                    ->where('rewardable_id', $referralProgram->id->toString()),
            ],
        ];
    }
}
