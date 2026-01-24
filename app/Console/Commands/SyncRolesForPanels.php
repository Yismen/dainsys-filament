<?php

namespace App\Console\Commands;

use App\Models\Role;
use Filament\Facades\Filament;
use Illuminate\Console\Command;

class SyncRolesForPanels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:sync-roles-for-panels {guard_name=web}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create roles corresponding to each panel';

    private array $types = ['manager', 'agent'];

    private array $defaultRoles = [
        ['name' => 'Super Admin', 'guard_name' => 'web'],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $panels = Filament::getPanels();
        $roles = $this->defaultRoles;

        $panelIDs = array_map(fn ($panel) => $panel->getId(), $panels);
        $panelIDs = \array_filter($panelIDs, fn ($panel) => $panel != 'admin');

        foreach ($panelIDs as $key => $value) {
            foreach ($this->types as $type) {
                $name = implode(' ', [
                    $key,
                    $type,
                ]);

                $roles[] = [
                    'name' => str($name)->trim(' ')->replace(['_', '-'], ' ')->title()->toString(),
                    'guard_name' => $this->argument('guard_name'),
                ];
            }
        }

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }

        $this->info('A total of '.\count($roles).' roles synced for all registered filament panels');

        return self::SUCCESS;
    }
}
