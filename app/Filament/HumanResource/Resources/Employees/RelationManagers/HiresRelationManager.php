<?php

namespace App\Filament\HumanResource\Resources\Employees\RelationManagers;

use App\Filament\HumanResource\Resources\Hires\HireResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class HiresRelationManager extends RelationManager
{
    protected static string $relationship = 'hires';

    protected static ?string $relatedResource = HireResource::class;

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
