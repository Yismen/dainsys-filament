<?php

namespace App\Filament\OperationsDirector\Resources\Evaluations;

use App\Filament\OperationsDirector\Resources\Evaluations\Pages\ListEvaluations;
use App\Filament\OperationsDirector\Resources\Evaluations\Schemas\EvaluationInfolist;
use App\Filament\OperationsDirector\Resources\Evaluations\Tables\EvaluationsTable;
use App\Models\Evaluation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EvaluationResource extends Resource
{
    protected static ?string $model = Evaluation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static ?string $navigationLabel = 'QA Evaluations';

    protected static ?int $navigationSort = 10;

    public static function infolist(Schema $schema): Schema
    {
        return EvaluationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EvaluationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvaluations::route('/'),
        ];
    }
}
