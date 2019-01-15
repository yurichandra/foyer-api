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
     * ServiceRegistry attributes.
     *
     * @var string
     */
    protected $service;

    /**
     * Create new command instance.
     *
     * @param ServiceRegistry $service
     */
    public function __construct(ServiceRegistry $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Method to handle console command.
     */
    public function handle()
    {
        
        $headers = ['id', 'name', 'url', 'slug', 'created_at', 'updated_at'];
        $services = $this->service->getServices();

        $this->table($headers, $services);
    }
}
