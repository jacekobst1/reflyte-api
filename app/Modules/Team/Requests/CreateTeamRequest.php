<?php

declare(strict_types=1);

namespace App\Modules\Team\Requests;

use App\Modules\User\User;
use Ramsey\Uuid\UuidInterface;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

class CreateTeamRequest extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $name,

        #[Required, Uuid, Exists(User::class, 'id')]
        public readonly UuidInterface $owner_user_id,
    ) {
    }
}
