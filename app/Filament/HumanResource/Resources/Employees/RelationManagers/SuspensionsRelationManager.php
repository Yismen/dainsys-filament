<?php

namespace App\Filament\HumanResource\Resources\Employees\RelationManagers;

use App\Filament\HumanResource\Resources\Suspensions\SuspensionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class SuspensionsRelationManager extends RelationManager
{
    protected static string $relationship = 'suspensions';

    protected static ?string $relatedResource = SuspensionResource::class;

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
