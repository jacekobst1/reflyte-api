<?php

declare(strict_types=1);

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Modules\Reward\Reward;
use App\Modules\Reward\RewardPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Reward::class => RewardPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $adminIps = Config::get('env.admin_ips');

        Gate::define('viewPulse', function ($user) use ($adminIps) {
            return
                in_array(request()->ip(), $adminIps, true)
                || $user->hasRole('admin');
        });
    }
}
