<?php

namespace App\Filament\HumanResource\Clusters\EmployeesManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class EmployeesManagementCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;
}
