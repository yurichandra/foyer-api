<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\HttpService;
use GuzzleHttp\Client;
use App\Routing\RouteRegistry;
use App\Services\ServiceRegistry;

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

        $this->app->singleton(RouteRegistry::class, function () {
            return new RouteRegistry();
        });

        $this->app->singleton(ServiceRegistry::class, function () {
            return new ServiceRegistry();
        });

        app(RouteRegistry::class)->registerRoutes(app());
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
