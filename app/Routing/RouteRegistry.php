<?php

namespace App\Routing;

use Laravel\Lumen\Application;
use App\Models\Route;
use Illuminate\Database\Eloquent\Collection;
use App\Services\ServiceRegistry;
use Illuminate\Support\Facades\DB;
use App\Exceptions\RouteDuplicationException;
use App\Exceptions\RouteCreationDuplication;
use App\Repositories\RouteRepository;

class RouteRegistry
{
    protected $service;

    protected $repo;

    public function __construct()
    {
        $this->service = new ServiceRegistry();
        $this->repo = new RouteRepository();
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
                'as' => $route['slug'],
            ];

            $app->router->{$method}($path, $params);
        });

        return true;
    }

    /**
     * Get all routes available in database.
     *
     * @return Collection
     */
    public function getRoutes(): Collection
    {
        return $this->repo->get();
    }

    /**
     * Method to add route based on service slug.
     *
     * @param string $slug
     * @param array $data
     */
    public function addRoute($slug, array $data)
    {
        try {
            $service_id = $this->service->findServiceBySlug($slug)->id;

            if (!$this->isRouteSlugUnique($data['slug'])) {
                throw new RouteDuplicationException();
            }

            $method = strtoupper($data['method']);

            DB::transaction(function () use ($data, $method, $service_id) {
                Route::create([
                    'service_id' => $service_id,
                    'path' => $data['path'],
                    'method' => $method,
                    'description' => $data['description'],
                    'slug' => $data['slug'],
                    'aggregate' => $data['aggregate'],
                    'protected' => $data['protected'],
                ]);
            });
        } catch (\Exception $e) {
            throw new RouteCreationDuplication();
        }
    }

    /**
     * Method to add route based on service slug.
     *
     * @param string $slug
     * @param array $data
     */
    public function updateRoute($slug, array $data)
    {
        try {
            $route = $this->findRouteBySlug($slug);

            $method = strtoupper($data['method']);

            DB::transaction(function () use ($data, $method, $route) {
                $route->update([
                    'path' => $data['path'],
                    'method' => $method,
                    'description' => $data['description'],
                    'slug' => $data['slug'],
                    'aggregate' => boolean($data['aggregate']),
                    'protected' => $data['protected'],
                ]);
            });

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to check whether route slug unique or not.
     *
     * @param string $slug
     * @return bool
     */
    private function isRouteSlugUnique($slug)
    {
        $route = Route::where('slug', $slug)->first();

        return is_null($route) ? true : false;
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
        return $this->repo->findRouteBySlug($slug);
    }
}
