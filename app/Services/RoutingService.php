<?php

namespace App\Services;

use Laravel\Lumen\Application;
use App\Models\Route;
use App\Models\Service;
use App\Repositories\ServiceRepository;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Exceptions\RouteNotRegisteredException;
use App\Exceptions\ServiceDuplicationException;
use App\Exceptions\ServiceCreationException;
use App\Exceptions\ServiceUpdateException;

class RoutingService
{
    /**
     * RoutingService constructor.
     */
    public function __construct()
    {
        $this->service_repo = new ServiceRepository();
    }

    /**
     * Register all routes
     *
     * @param Application $app
     */
    public function registerRoutes(Application $app)
    {
        $this->getRoutes()->each(function ($route) use ($app) {
            $method = strtolower($route['method']);
            $path = $route['path'];

            $params = [
                'uses' => '\App\Http\Controllers\GatewayController@' . $method,
            ];

            $app->router->{$method}($path, $params);
        });

        return true;
    }

    /**
     * Get all routes available in database.
     *
     * @param int service_id
     */
    public function getRoutes($service_id = null): Collection
    {
        if (is_null($service_id)) {
            return Route::with('service')->get();
        }

        return $this->findRouteByService($service_id);
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
            $service = Service::find('slug', $slug)->firstOrFail();

            return $service;
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
    public function updateService($id, array $data)
    {
        try {
            $service = $this->findService($id);

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
     * Find route based on service
     *
     * @param int $id
     */
    protected function findRouteByService($id)
    {
        return $this->findService($id)->routes;
    }

    /**
     * Get route data based on slug.
     *
     * @param string $key
     * @return array
     */
    public function getRouteData($key)
    {
        try {
            $route = $this->findRouteBySlug($key);

            return [
                'url' => $route->service->url . $route->path,
                'method' => $route->method,
                'description' => $route->description,
                'protected' => $route->protected,
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Find route by a slug.
     *
     * @param string $slug
     * @return Route $route
     */
    public function findRouteBySlug($slug)
    {
        try {
            $route = Route::where('slug', $slug)->first();

            return $route;
        } catch (\Exception $e) {
            throw $e;
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
