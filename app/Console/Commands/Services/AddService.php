<?php

namespace App\Console\Commands\Services;

use Illuminate\Console\Command;
use App\Services\ServiceRegistry;

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
     * Method to handle console command.
     */
    public function handle(ServiceRegistry $service)
    {
        $name = $this->ask('Name of service?');
        $url = $this->ask('URL of service?');
        $slug = $this->ask('Slug of service?');

        try {
            $service->addService([
                'name' => $name,
                'url' => $url,
                'slug' => $slug
            ]);

            $this->info($name . ' successfully added as a service');
        } catch (\Exception $e) {
            $this->info($e->getMessage());
        }
    }
}
