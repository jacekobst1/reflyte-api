<?php

declare(strict_types=1);

test('Debugging code is not present in the codebase')
    ->expect(['dd', 'dump', 'var_dump', 'print_r', 'die', 'exit', 'phpinfo', 'xdebug', 'ray'])
    ->each()
    ->not()->toBeUsed();
