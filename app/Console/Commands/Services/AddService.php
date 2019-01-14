<?php

namespace App\Console\Commands\Services;

use Illuminate\Console\Command;
use App\Services\RoutingService;

class AddService extends Command
{
    /**
     * Signature of console command.
     *
     * @var string
     */
    protected $signature = 'foyer:add-service';

    /**
     * Description of console command.
     *
     * @var string
     */
    protected $description = 'Add a service';

    /**
     * Service attributes.
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
        $name = $this->ask('Name of service?');
        $url = $this->ask('URL of service?');
        $slug = $this->ask('Slug of service?');

        try {
            $this->service->addService([
                'name' => $name,
                'url' => $url,
                'slug' => $slug
            ]);

            $this->info($name . 'successfully added as a service');
        } catch (\Exception $e) {
            $this->info($e->getMessage());
        }
    }
}
