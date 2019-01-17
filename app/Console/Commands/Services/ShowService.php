<?php

namespace App\Console\Commands\Services;

use Illuminate\Console\Command;
use App\Services\ServiceRegistry;

class ShowService extends Command
{
    /**
     * Signature of console command.
     *
     * @var string
     */
    protected $signature = 'foyer:show-service';

    /**
     * Description of console command.
     *
     * @var string
     */
    protected $description = 'Show services available';

    /**
     * Method to handle console command.
     */
    public function handle(ServiceRegistry $service)
    {
        $headers = ['id', 'name', 'url', 'slug', 'created_at', 'updated_at'];
        $services = $service->getServices();

        $this->table($headers, $services);
    }
}
