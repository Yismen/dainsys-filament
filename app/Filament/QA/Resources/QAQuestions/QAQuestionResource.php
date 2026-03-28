<?php

namespace App\Filament\QA\Resources\QAQuestions;

use App\Filament\QA\Resources\QAQuestions\Pages\CreateQAQuestion;
use App\Filament\QA\Resources\QAQuestions\Pages\EditQAQuestion;
use App\Filament\QA\Resources\QAQuestions\Pages\ListQAQuestions;
use App\Filament\QA\Resources\QAQuestions\Pages\ViewQAQuestion;
use App\Filament\QA\Resources\QAQuestions\Schemas\QAQuestionForm;
use App\Filament\QA\Resources\QAQuestions\Schemas\QAQuestionInfolist;
use App\Filament\QA\Resources\QAQuestions\Tables\QAQuestionsTable;
use App\Models\QAQuestion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;

class QAQuestionResource extends Resource
{
    protected static ?string $model = QAQuestion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static ?string $navigationLabel = 'QA Questions';

    protected static ?string $recordTitleAttribute = 'text';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return QAQuestionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QAQuestionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QAQuestionsTable::configure($table);
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
            'index' => ListQAQuestions::route('/'),
            'create' => CreateQAQuestion::route('/create'),
            'view' => ViewQAQuestion::route('/{record}'),
            'edit' => EditQAQuestion::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Gate::allows('manageQAQuestions');
    }

    public static function canCreate(): bool
    {
        return Gate::allows('manageQAQuestions');
    }

    public static function canEdit(Model $record): bool
    {
        return Gate::allows('manageQAQuestions');
    }

    public static function canDelete(Model $record): bool
    {
        return Gate::allows('manageQAQuestions');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
