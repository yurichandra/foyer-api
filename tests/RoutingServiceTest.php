<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Services\RoutingService;
use App\Exceptions\ServiceDuplicationException;

class RoutingServiceTest extends TestCase
{
    /**
     * A add service test.
     *
     * @return void
     */
    public function testAddService()
    {
        $service = new RoutingService();

        $service_created = $service->addService([
            'name' => 'Student Service',
            'url' => 'http://127.0.0.1:7000',
            'slug' => 'student.service',
        ]);

        $this->assertTrue($service_created);
    }

    public function testUniqueSlugWhenAddService()
    {
        $this->expectException(ServiceDuplicationException::class);

        $service = new RoutingService();

        $service_created = $service->addService([
            'name' => 'Student Service',
            'url' => 'http://127.0.0.1:7000',
            'slug' => 'student.service',
        ]);
    }
}
