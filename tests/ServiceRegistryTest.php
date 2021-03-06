<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Exceptions\ServiceDuplicationException;
use App\Models\Service;
use Faker\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\ServiceRegistry;

class ServiceRegistryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A add service test.
     *
     * @return void
     */
    public function testAddService()
    {
        $service = new ServiceRegistry();

        $service_created = $service->addService([
            'name' => 'Student Service',
            'url' => 'http://127.0.0.1:7000',
            'slug' => 'student.service',
        ]);

        $this->assertTrue($service_created);
    }

    /**
     * A test to check whether slug unique.
     *
     * @return void
     */
    public function testUniqueSlugWhenAddService()
    {
        $this->expectException(ServiceDuplicationException::class);

        $service = new ServiceRegistry();

        $service_created = $service->addService([
            'name' => 'User Service',
            'url' => 'http://127.0.0.1:7000',
            'slug' => 'user.service',
        ]);
    }

    /**
     * A test to find service by its slug.
     *
     *  @return void
     */
    public function testFindServiceBySlug()
    {
        $service = new ServiceRegistry();
        $faker = Factory::create();

        $services_slugs = $service->getServices()
                            ->map(function ($item) {
                                return $item->slug;
                            });

        $service_found = $service->findServiceBySlug($faker->randomElements($services_slugs));

        $this->assertInstanceOf(Service::class, $service_found);
    }

    /**
     * A test to update service by its slug.
     *
     *  @return void
     */
    public function testUpdateService()
    {
        $service = new ServiceRegistry();
        $updated_service = $service->updateService('user.service', ['url' => 'http://127.0.0.1:1000']);

        $this->assertTrue($updated_service);
    }

    // /**
    //  * A test to delete service by its slug.
    //  *
    //  *  @return void
    //  */
    // public function testDeleteService()
    // {
    //     $this->expectException(ModelNotFoundException::class);
    //     $service = new ServiceRegistry();
    //     $faker = Factory::create();

    //     $services_slugs = $service->getServices()
    //         ->map(function ($item) {
    //             return $item->slug;
    //         });

    //     $slug = $faker->randomElement($services_slugs);

    //     $service_deleted = $service->deleteService($slug);

    //     $find_service = $service->findServiceBySlug($slug);
    // }
}
