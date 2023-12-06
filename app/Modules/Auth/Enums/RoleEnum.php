<?php

declare(strict_types=1);

namespace App\Modules\Auth\Enums;

enum RoleEnum: string
{
    case User = 'user';
    case Admin = 'admin';
}
