<?php

namespace App\Filament\HumanResource\Resources\SocialSecurities\Pages;

use App\Filament\HumanResource\Resources\SocialSecurities\SocialSecurityResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSocialSecurity extends EditRecord
{
    protected static string $resource = SocialSecurityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
