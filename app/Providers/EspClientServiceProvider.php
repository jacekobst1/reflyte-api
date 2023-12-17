<?php

declare(strict_types=1);

namespace App\Providers;

use App\Modules\Esp\Integration\EspClientFactory;
use App\Modules\Esp\Integration\EspClientInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class EspClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(EspClientInterface::class, function (Application $app) {
            $user = Auth::user();
            $newsletter = $user->getNewsletter();

            if (!$newsletter) {
                return null;
            }

            $clientFactory = $app->make(EspClientFactory::class);

            return $clientFactory->make($newsletter->getEspConfig());
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
