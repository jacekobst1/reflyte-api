<?php

namespace App\Modules\Users\Requests;

use _PHPStan_93af41bf5\Symfony\Contracts\Service\Attribute\Required;
use App\Models\User;
use Laravel\Fortify\Rules\Password;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class CreateUserRequest extends Data
{
    public function __construct(
        #[Required, StringType, Max(255)]
        public readonly string $name,

        #[Required, StringType, Email, Max(255), Unique(User::class)]
        public readonly string $email,

        #[Required, StringType, Rule(new Password())]
        public readonly string $password,
    ) {
    }
}
