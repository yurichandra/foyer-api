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
use Illuminate\Support\Facades\Cache;

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
        collect($this->getFinalRoutes())->each(function ($route) use ($app) {
            $method = strtolower($route['method']);
            $path = $route['path'];

            $params = [
                'uses' => '\App\Http\Controllers\GatewayController@' . $method,
                'as' => $route['as'],
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
                    'protected' => boolean($data['protected']),
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
            return $this->normalizeRoute()[$key];
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
    protected function findRouteBySlug($slug)
    {
        return $this->repo->findRouteBySlug($slug);
    }

    /**
     * Normalize for send routes data.
     *
     * @return array
     */
    protected function normalizeRoute()
    {
        $routes = $this->getRoutes()
            ->reduce(function ($carry, $route) {
                $slug = $route->slug;

                $carry[$slug] = [
                    'url' => $route->service->url . $route->path,
                    'path' => $route->path,
                    'method' => $route->method,
                    'as' => $slug,
                    'description' => $route->description,
                    'protected' => $route->protected,
                ];

                return $carry;
            }, []);

        return $routes;
    }
    
    /**
     * Caching the routes
     *
     * @return boolean
     */
    protected function cacheRoute()
    {
        $routes = json_encode($this->normalizeRoute());
        Cache::forever('routes', $routes);

        return true;
    }

    /**
     * Return normalized routes.
     */
    protected function getFinalRoutes()
    {
        if (env('CACHE_ROUTES')) {
            $this->cacheRoute();
            return $this->getCacheRoutes();
        }

        return $this->normalizeRoute();
    }

    /**
     * Return routes that were cached.
     */
    protected function getCacheRoutes()
    {
        $routes = Cache::get('routes');
        
        return json_decode($routes, true);
    }
}
