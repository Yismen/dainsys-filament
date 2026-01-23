<?php

namespace Database\Seeders;

use App\Enums\RevenueTypes;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\Downtime;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Holiday;
use App\Models\Production;
use App\Models\Role;
use App\Models\Source;
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

        cache()->flush();

        User::factory()->count(25)->createQuietly();

        Hire::factory()->count(5)->createQuietly(); // this will create employees, sites, positions, supervisors

        Client::factory()->count(5)->createQuietly();

        (array) $user_data = [
            'email' => 'yismen.jorge@gmail.com',
            'name' => 'Yismen Jorge',
        ];

        $user = User::query()->firstOrCreate(
            $user_data,
            User::factory()->make($user_data)->getAttributes()
        );
        $admin_role = Role::firstOrCreate([
            'guard_name' => 'web',
            'name' => 'Super Admin',
        ]);

        $user->assignRole($admin_role);

        $downtimeSource = Source::firstOrCreate(['name' => 'Downtime']);

        $downtimeCampaign = Campaign::factory()->for($downtimeSource)->createQuietly(['revenue_type' => RevenueTypes::Downtime]);

        foreach (Employee::take(5)->get() as $employee) {
            Production::factory()->for($employee)->createQuietly();
            Downtime::factory()->for($employee)->for($downtimeCampaign)->createQuietly();
        }

        // Holiday::factory()->createQuietly(['date' => now()->subDay()]);

        // SuspensionType::factory()->createQuietly(['name' => 'Maternal Leave']);

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
