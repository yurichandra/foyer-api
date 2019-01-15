<?php

namespace App\Routing;

use Laravel\Lumen\Application;
use App\Models\Route;
use Illuminate\Database\Eloquent\Collection;

class RouteRegistry
{
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
    public function getRoutes($service_id = null) : Collection
    {
        if (is_null($service_id)) {
            return Route::with('service')->get();
        }

        return $this->findRouteByService($service_id);
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
}
