<?php

namespace App\Console\Commands\Services;

use Illuminate\Console\Command;
use App\Services\ServiceRegistry;

class UpdateService extends Command
{
    /**
     * Signature of console command.
     *
     * @var string
     */
    protected $signature = 'foyer:update-service';

    /**
     * Description of console command.
     *
     * @var string
     */
    protected $description = 'Update a services available';

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
            $url = $this->ask('URL of service?');
            
            $this->service->updateService($slug, ['url' => $url]);
            $this->info($slug . ' has been updated');
        } catch (\Exception $e) {
            $this->info($e->getMessage());
        }
    }
}
