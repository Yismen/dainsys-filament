<?php

namespace App\Filament\HumanResource\Resources\Employees\RelationManagers;

use App\Filament\HumanResource\Resources\SocialSecurities\SocialSecurityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class SocialSecuritiesRelationManager extends RelationManager
{
    protected static string $relationship = 'socialSecurity';

    protected static ?string $relatedResource = SocialSecurityResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->recordActions([
            ])
            ->openRecordUrlInNewTab()
            ->headerActions([
                // CreateAction::make(),
            ]);
    }
}
