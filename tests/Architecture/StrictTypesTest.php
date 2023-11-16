<?php

declare(strict_types=1);

test('Every class has strict_types declaration')
    ->expect('App')
    ->toUseStrictTypes();
