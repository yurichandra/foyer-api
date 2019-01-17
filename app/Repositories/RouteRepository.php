<?php

namespace App\Repositories;

use App\Models\Route;

class RouteRepository extends Repository
{
    protected $model = Route::class;

    public function delete(Route $route)
    {
        return $route->delete();
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
            $route = $this->model::where('slug', $slug)->first();

            return $route;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
