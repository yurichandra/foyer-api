<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\HttpService;
use App\Services\RoutingService;
use GuzzleHttp\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Method to load all dependencies when boot the apps.
     */
    public function boot()
    {
        $this->app->singleton(HttpService::class, function () {
            $client = new Client();

            return new HttpService($client);
        });

        $this->app->singleton(RoutingService::class, function () {
            return new RoutingService();
        });

        app(RoutingService::class)->registerRoutes(app());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
