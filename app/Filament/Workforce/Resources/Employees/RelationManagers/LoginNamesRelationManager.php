<?php

namespace App\Filament\Workforce\Resources\Employees\RelationManagers;

use App\Filament\Workforce\Resources\LoginNames\LoginNameResource;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;

class LoginNamesRelationManager extends RelationManager
{
    protected static string $relationship = 'loginNames';

    protected static ?string $relatedResource = LoginNameResource::class;

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
