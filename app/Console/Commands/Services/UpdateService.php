<?php

namespace App\Console\Commands\Services;

use Illuminate\Console\Command;
use App\Services\RoutingService;

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
            $url = $this->ask('URL of service?');
            
            $this->service->updateService($slug, ['url' => $url]);
            $this->info($slug . ' has been updated');
        } catch (\Exception $e) {
            $this->info($e->getMessage());
        }
    }
}
