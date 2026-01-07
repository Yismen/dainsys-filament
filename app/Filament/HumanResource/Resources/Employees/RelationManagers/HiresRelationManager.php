<?php

namespace App\Filament\HumanResource\Resources\Employees\RelationManagers;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\HumanResource\Resources\Hires\HireResource;

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
