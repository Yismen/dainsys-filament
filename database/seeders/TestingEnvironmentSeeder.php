<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Downtime;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Production;
use App\Models\Role;
use App\Models\SuspensionType;
use App\Models\Termination;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;

class TestingEnvironmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->isProduction() === true) {
            throw new Exception('Invalid environment', 423);
        }

        User::factory()->count(25)->create();

        Hire::factory()->count(5)->create();

        Client::factory()->count(5)->create();

        (array)$user_data = [
            'email' => 'yismen.jorge@gmail.com',
            'name' => 'Yismen Jorge',
        ];

        $user = User::query()->firstOrCreate(
            $user_data,
            User::factory()->make($user_data)->getAttributes()
        );

        cache()->flush();
        $admin_role = Role::firstOrCreate([
            'guard_name' => 'web',
            'name' => 'super admin',
        ]);

        $user->assignRole($admin_role);

        foreach(Employee::take(5)->get() as $employee) {
            // Termination::factory()->for($employee)->create();

            Production::factory()->for($employee)->create();
            Downtime::factory()->for($employee)->create();
        }

        SuspensionType::factory()->create(['name' => 'Maternal Leave']);

        /**
         * bank account
         * social security
         * termination
         * production
         * downtime
         * holidays
         */
    }
}
