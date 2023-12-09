<?php

declare(strict_types=1);

namespace App\Providers;

use App\Modules\ESP\Integration\ClientFactory;
use App\Modules\ESP\Integration\ClientInterface;
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
        $this->app->bind(ClientInterface::class, function (Application $app) {
            $user = Auth::user();
            $newsletter = $user->getNewsletter();

            if (!$newsletter) {
                return null;
            }

            $clientFactory = $app->make(ClientFactory::class);

            return $clientFactory->make($newsletter->esp_name, $newsletter->esp_api_key);
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
