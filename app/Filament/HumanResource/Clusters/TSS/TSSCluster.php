<?php

namespace App\Filament\HumanResource\Clusters\TSS;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class TSSCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Key;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
}
