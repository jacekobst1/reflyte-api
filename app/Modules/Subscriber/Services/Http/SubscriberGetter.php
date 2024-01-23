<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Services\Http;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

final class SubscriberGetter
{

    public function paginateByLoggedUser(): LengthAwarePaginator
    {
        $newsletter = Auth::user()->getNewsletter();

        return $newsletter->subscribers()->paginate();
    }
}
