<?php

namespace App\Filament\QA\Resources\Evaluations;

use App\Enums\QARoles;
use App\Filament\QA\Resources\Evaluations\Pages\CreateEvaluation;
use App\Filament\QA\Resources\Evaluations\Pages\EditEvaluation;
use App\Filament\QA\Resources\Evaluations\Pages\ListEvaluations;
use App\Filament\QA\Resources\Evaluations\Pages\ViewEvaluation;
use App\Filament\QA\Resources\Evaluations\Schemas\EvaluationForm;
use App\Filament\QA\Resources\Evaluations\Schemas\EvaluationInfolist;
use App\Filament\QA\Resources\Evaluations\Tables\EvaluationsTable;
use App\Models\Evaluation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EvaluationResource extends Resource
{
    protected static ?string $model = Evaluation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return EvaluationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EvaluationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EvaluationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvaluations::route('/'),
            'create' => CreateEvaluation::route('/create'),
            'view' => ViewEvaluation::route('/{record}'),
            'edit' => EditEvaluation::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Gate::allows('interactsWithQualityAssurance');
    }

    public static function canCreate(): bool
    {
        return Gate::allows('createQAEvaluations');
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();

        if ($user === null) {
            return false;
        }

        return $user->hasRole(QARoles::Agent->value)
            && $record->evaluator_id === $user->id
            && $record->status->value === 'draft';
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user === null) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasRole(QARoles::Manager->value)) {
            return $query;
        }

        if ($user->hasRole(QARoles::Agent->value)) {
            return $query->where('evaluator_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }
}
