<?php

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create([
            'name' => 'User Service',
            'url' => 'http://127.0.0.1:9000'
        ]);
    }
}
