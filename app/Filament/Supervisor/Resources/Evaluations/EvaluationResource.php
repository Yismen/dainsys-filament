<?php

namespace App\Filament\Supervisor\Resources\Evaluations;

use App\Filament\Supervisor\Resources\Evaluations\Pages\ListEvaluations;
use App\Filament\Supervisor\Resources\Evaluations\Schemas\EvaluationInfolist;
use App\Filament\Supervisor\Resources\Evaluations\Tables\EvaluationsTable;
use App\Models\Evaluation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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

    public static function getEloquentQuery(): Builder
    {
        $supervisor = Auth::user()?->supervisor;

        if (! $supervisor) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->whereHas('employee', function (Builder $query) use ($supervisor): void {
                $query->where('supervisor_id', $supervisor->id);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvaluations::route('/'),
        ];
    }
}
