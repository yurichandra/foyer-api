<?php

namespace App\Console\Commands\Services;

use Illuminate\Console\Command;
use App\Services\RoutingService;

class DeleteService extends Command
{
    /**
     * Signature of console command.
     *
     * @var string
     */
    protected $signature = 'foyer:delete-service';

    /**
     * Description of console command.
     *
     * @var string
     */
    protected $description = 'Delete a service available';

    /**
     * RoutingService attributes.
     *
     * @var string
     */
    protected $service;

    /**
     * Create new command instance.
     *
     * @param RoutingService $service
     */
    public function __construct(RoutingService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Method to handle console command.
     */
    public function handle()
    {
        try {
            $slug = $this->ask('Slug of service?');

            $this->service->deleteService($slug);
            $this->info($slug . ' has been deleted');
        } catch (\Exception $e) {
            $this->info($e->getMessage());
        }
    }
}
