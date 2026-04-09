<?php

namespace App\Filament\Invoicing\Resources\Campaigns\Pages;

use App\Filament\Invoicing\Resources\Campaigns\CampaignResource;
use Filament\Resources\Pages\ManageRecords;

class ManageCampaigns extends ManageRecords
{
    protected static string $resource = CampaignResource::class;
}
