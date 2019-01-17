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
     * Method to handle console command.
     */
    public function handle(ServiceRegistry $service)
    {
        try {
            $slug = $this->ask('Slug of service?');
            $url = $this->ask('URL of service?');
            
            $service->updateService($slug, ['url' => $url]);
            $this->info($slug . ' has been updated');
        } catch (\Exception $e) {
            $this->info($e->getMessage());
        }
    }
}
