<?php

declare(strict_types=1);

use App\Http\Controllers\Controller;

test('Every controller extends the base controller')
    ->expect('App\Http\Controllers')
    ->toExtend(Controller::class);

//test('Every Controller use Resource classes when responding')
//    ->expect('App\Http\Resources')
//    ->toBeUsedIn('App\Http\Controllers');
