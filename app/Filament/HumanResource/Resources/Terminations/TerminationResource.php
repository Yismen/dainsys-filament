<?php

namespace App\Filament\HumanResource\Resources\Terminations;

use App\Filament\HumanResource\Resources\Terminations\Pages\CreateTermination;
use App\Filament\HumanResource\Resources\Terminations\Pages\EditTermination;
use App\Filament\HumanResource\Resources\Terminations\Pages\ListTerminations;
use App\Filament\HumanResource\Resources\Terminations\Pages\ViewTermination;
use App\Filament\HumanResource\Resources\Terminations\Schemas\TerminationForm;
use App\Filament\HumanResource\Resources\Terminations\Schemas\TerminationInfolist;
use App\Filament\HumanResource\Resources\Terminations\Tables\TerminationsTable;
use App\Models\Termination;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TerminationResource extends Resource
{
    protected static ?string $model = Termination::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStop;

    protected static ?string $recordTitleAttribute = 'id';

    protected static \UnitEnum|string|null $navigationGroup = \App\Filament\HumanResource\Enums\HRNavigationEnum::EMPLOYEES_MANAGEMENT;

    protected static ?int $navigationSort = 3;

    public static function getRecordTitle(?Model $record): string|Htmlable|null
    {
        return $record ? $record->employee->full_name : static::getModelLabel();
    }

    public static function form(Schema $schema): Schema
    {
        return TerminationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TerminationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TerminationsTable::configure($table);
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
            'index' => ListTerminations::route('/'),
            'create' => CreateTermination::route('/create'),
            'view' => ViewTermination::route('/{record}'),
            'edit' => EditTermination::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
