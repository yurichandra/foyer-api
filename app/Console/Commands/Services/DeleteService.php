<?php

namespace App\Console\Commands\Services;

use Illuminate\Console\Command;
use App\Services\ServiceRegistry;

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
        try {
            $slug = $this->ask('Slug of service?');

            $this->service->deleteService($slug);
            $this->info($slug . ' has been deleted');
        } catch (\Exception $e) {
            $this->info($e->getMessage());
        }
    }
}
