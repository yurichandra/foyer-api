<?php

namespace App\Console\Commands\Routes;

use Illuminate\Console\Command;
use App\Routing\RouteRegistry;

class AddRoute extends Command
{
    protected $signature = 'foyer:add-route';

    protected $description = 'Add a route';

    public function handle(RouteRegistry $route)
    {
        $slug_service = $this->ask('What slug of a service that you want to add?');
        $path = $this->ask('Path of route?');
        $method = $this->ask('Method of route?');
        $slug = $this->ask('Slug of route?');
        $description = $this->ask('Description of route?');
        $protected = $this->option('Is it protected?', ['true', 'false']);
        $aggregate = $this->option('Is it include aggreggate?', ['true', 'false']);

        try {
            $route->addRoute($slug_service, [
                'path' => $path,
                'method' => $method,
                'slug' => $slug,
                'description' => $description,
                'protected' => $protected,
                'aggregate' => $aggregate,
            ]);

            $this->info($slug . ' successfully added as a route');
        } catch (\Exception $e) {
            $this->info($e->getMessage());
        }
    }
}
