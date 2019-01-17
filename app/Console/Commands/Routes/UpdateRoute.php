<?php

namespace App\Console\Commands\Routes;

use Illuminate\Console\Command;
use App\Routing\RouteRegistry;

class UpdateRoute extends Command
{
    /**
     * Signature of console command.
     *
     * @var string
     */
    protected $signature = 'foyer:update-route';

    /**
     * Description of console command.
     *
     * @var string
     */
    protected $description = 'Update a routes available';

    /**
     * Method to handle console command.
     */
    public function handle(RouteRegistry $route)
    {
        try {
            $slug = $this->ask('What slug of a route that you want to update?');
            $path = $this->ask('Path of route?');
            $method = $this->ask('Method of route?');
            $new_slug = $this->ask('Slug of route?');
            $description = $this->ask('Description of route?');
            $protected = $this->choice('Is it protected?', ['true', 'false']);
            $aggregate = $this->choice('Is it include aggreggate?', ['true', 'false']);

            $route->updateRoute($slug, [
                'slug' => $new_slug,
                'method' => $method,
                'path' => $path,
                'description' => $description,
                'aggregate' => $aggregate === 'true' ? true : false,
                'protected' => $protected === 'true' ? true : false,
            ]);

            $this->info($slug . ' has been updated');
        } catch (\Exception $e) {
            $this->info($e->getMessage());
        }
    }
}
