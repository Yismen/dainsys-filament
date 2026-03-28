<?php

namespace App\Filament\QA\Resources\QAForms;

use App\Filament\QA\Resources\QAForms\Pages\CreateQAForm;
use App\Filament\QA\Resources\QAForms\Pages\EditQAForm;
use App\Filament\QA\Resources\QAForms\Pages\ListQAForms;
use App\Filament\QA\Resources\QAForms\Pages\ViewQAForm;
use App\Filament\QA\Resources\QAForms\Schemas\QAFormForm;
use App\Filament\QA\Resources\QAForms\Schemas\QAFormInfolist;
use App\Filament\QA\Resources\QAForms\Tables\QAFormsTable;
use App\Models\QAForm;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;

class QAFormResource extends Resource
{
    protected static ?string $model = QAForm::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'QA Forms';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return QAFormForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QAFormInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QAFormsTable::configure($table);
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
            'index' => ListQAForms::route('/'),
            'create' => CreateQAForm::route('/create'),
            'view' => ViewQAForm::route('/{record}'),
            'edit' => EditQAForm::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Gate::allows('manageQAForms');
    }

    public static function canCreate(): bool
    {
        return Gate::allows('manageQAForms');
    }

    public static function canEdit(Model $record): bool
    {
        return Gate::allows('manageQAForms');
    }

    public static function canDelete(Model $record): bool
    {
        return Gate::allows('manageQAForms');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
