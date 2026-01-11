<?php

namespace App\Filament\HumanResource\Resources\Employees\RelationManagers;

use App\Filament\HumanResource\Resources\Terminations\TerminationResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TerminationsRelationManager extends RelationManager
{
    protected static string $relationship = 'terminations';

    protected static ?string $relatedResource = TerminationResource::class;

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
