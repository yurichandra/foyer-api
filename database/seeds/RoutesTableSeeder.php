<?php

use Illuminate\Database\Seeder;
use App\Models\Route;

class RoutesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Route::create([
            'service_id' => 1,
            'path' => '/users',
            'method' => 'GET',
            'description' => 'fetch all users',
            'slug' => 'users.get',
            'aggregate' => false,
            'protected' => false,
        ]);
    }
}
