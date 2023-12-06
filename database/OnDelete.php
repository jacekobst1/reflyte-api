<?php

declare(strict_types=1);

namespace Database;

final class OnDelete
{
    public const CASCADE = 'CASCADE';
    public const SET_NULL = 'SET NULL';
    public const SET_DEFAULT = 'SET DEFAULT';
    public const RESTRICT = 'RESTRICT';
    public const DO_NOTHING = 'DO NOTHING';
}
