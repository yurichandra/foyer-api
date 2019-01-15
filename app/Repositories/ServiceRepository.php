<?php

namespace App\Repositories;

use App\Models\Service;

class ServiceRepository extends Repository
{
    protected $model = Service::class;

    public function delete(Service $service)
    {
        return $service->delete();
    }
}
