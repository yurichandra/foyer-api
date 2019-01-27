<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Faker\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Routing\RouteRegistry;
use App\Models\Route;

class RouteRegistryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A test to find service by its slug.
     *
     *  @return void
     */
    public function testFindServiceBySlug()
    {
        $route = new RouteRegistry();
        $faker = Factory::create();

        $routes_slugs = $route->getRoutes()
            ->map(function ($item) {
                return $item->slug;
            });

        $route_found = $route->findRouteBySlug($routes_slugs->first());

        $this->assertInstanceOf(Route::class, $route_found);
    }

    /**
     * A test to update service by its slug.
     *
     *  @return void
     */
    public function testUpdateService()
    {
        $route = new RouteRegistry();
        $updated_route = $route->updateRoute('user.delete', [
            'path' => '/users/{$id}',
            'method' => 'delete',
            'slug' => 'users.delete',
            'description' => 'Testing Update',
            'aggregate' => false,
            'protected' => false,
        ]);

        $this->assertTrue($updated_route);
    }
}
