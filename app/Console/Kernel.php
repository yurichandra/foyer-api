<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use App\Console\Commands\Services\AddService;
use App\Console\Commands\Services\ShowService;
use App\Console\Commands\Services\UpdateService;
use App\Console\Commands\Services\DeleteService;
use App\Console\Commands\Routes\AddRoute;
use App\Console\Commands\Routes\ShowRoute;
use App\Console\Commands\Routes\UpdateRoute;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AddService::class,
        ShowService::class,
        UpdateService::class,
        DeleteService::class,
        AddRoute::class,
        ShowRoute::class,
        UpdateRoute::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
