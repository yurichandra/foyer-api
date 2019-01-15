<?php

namespace App\Services;

use App\Models\Service;
use App\Repositories\ServiceRepository;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ServiceDuplicationException;
use App\Exceptions\ServiceCreationException;
use App\Exceptions\ServiceUpdateException;

class ServiceRegistry
{
    /**
     * ServiceRegistry constructor.
     */
    public function __construct()
    {
        $this->service_repo = new ServiceRepository();
    }

    /**
     * Find service based on id
     *
     * @param int $id
     */
    public function findService($id)
    {
        return $this->service_repo->find($id);
    }

    /**
     * Method to add a service.
     *
     * @param array $data.
     * @return $service.
     */
    public function addService(array $data)
    {
        if (!$this->isServiceSlugUnique($data['slug'])) {
            throw new ServiceDuplicationException();
        }

        try {
            DB::transaction(function () use ($data) {
                $service = Service::create($data);
            });

            return true;
        } catch (\Exception $e) {
            throw new ServiceCreationException();
        }
    }

    /**
     * Method to fetch all available services.
     *
     * @return Collection
     */
    public function getServices()
    {
        return $this->service_repo->get();
    }

    /**
     * Find registered service by a slug.
     *
     * @param string $slug
     * @return Service $service
     */
    public function findServiceBySlug($slug)
    {
        try {
            $service = Service::where('slug', $slug)->firstOrFail();

            return $service;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete registered service by a slug.
     *
     * @param string $slug
     * @return Service $service
     */
    public function deleteService($slug)
    {
        try {
            $service = Service::where('slug', $slug)->firstOrFail();

            return $this->service_repo->delete($service);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to update service.
     *
     * @param int $id
     * @param array $data
     *
     * @return bool
     */
    public function updateService($slug, array $data)
    {
        try {
            $service = $this->findServiceBySlug($slug);

            DB::transaction(function () use ($service, $data) {
                $service->update($data);
            });

            return true;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ServiceUpdateException();
        }
    }

    /**
     * Method to check whether service slug unique or not.
     *
     * @param string $slug
     * @return bool
     */
    protected function isServiceSlugUnique($slug)
    {
        $service = Service::where('slug', $slug)->first();

        return is_null($service) ? true : false;
    }
}
