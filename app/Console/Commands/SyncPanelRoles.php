<?php

namespace App\Console\Commands;

use Filament\Facades\Filament;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class SyncPanelRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-panel-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create roles corresponding to each panel';

    private array $roles = [];

    private array $panelIDs = [];

    private array $types = ['manager', 'user'];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $panels = Filament::getPanels();

        $this->panelIDs = array_map(fn ($panel) => $panel->getId(), $panels);

        foreach ($this->panelIDs as $key => $value) {
            foreach ($this->types as $type) {
                $this->roles[] = [
                    'name' => implode('-', [
                        $key,
                        $type,
                    ]),
                    'guard_name' => 'web',
                ];
            }
        }

        Role::upsert($this->roles, ['name', 'guard_name'], ['name']);

        $this->warn('Roles synced for all registered filament panels');
    }
}
