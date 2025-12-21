<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Hire;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestingEnvironmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(app()->isProduction() === true) {
            throw new Exception('Invalid environment', 423);
        }

        $user = User::factory()->count(25)->create();

        $hires = Hire::factory()->count(25)->create();

        $clients = Client::factory()->count(5)->create();



        /**
         * employee
         * bank account
         * hire
         * client
         * social security
         * termination
         * production
         * downtime
         * holidays
         *
         */
    }
}
