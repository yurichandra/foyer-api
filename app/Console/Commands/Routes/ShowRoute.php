<?php

namespace App\Console\Commands\Routes;

use Illuminate\Console\Command;
use App\Services\ServiceRegistry;
use App\Routing\RouteRegistry;

class ShowRoute extends Command
{
    /**
     * Signature of console command.
     *
     * @var string
     */
    protected $signature = 'foyer:show-route';

    /**
     * Description of console command.
     *
     * @var string
     */
    protected $description = 'Show routes available';

    /**
     * Method to handle console command.
     */
    public function handle(RouteRegistry $route)
    {
        $headers = ['id', 'path', 'method', 'description', 'slug', 'aggreggate'];
        $routes = $route->getRoutes();

        $this->table($headers, $routes);
    }
}
