<?php

namespace App\Filament\Workforce\Resources\DowntimeReasons;

use App\Filament\Workforce\Resources\DowntimeReasons\Pages\CreateDowntimeReason;
use App\Filament\Workforce\Resources\DowntimeReasons\Pages\EditDowntimeReason;
use App\Filament\Workforce\Resources\DowntimeReasons\Pages\ListDowntimeReasons;
use App\Filament\Workforce\Resources\DowntimeReasons\Pages\ViewDowntimeReason;
use App\Filament\Workforce\Resources\DowntimeReasons\Schemas\DowntimeReasonForm;
use App\Filament\Workforce\Resources\DowntimeReasons\Schemas\DowntimeReasonInfolist;
use App\Filament\Workforce\Resources\DowntimeReasons\Tables\DowntimeReasonsTable;
use App\Models\DowntimeReason;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DowntimeReasonResource extends Resource
{
    protected static ?string $model = DowntimeReason::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return DowntimeReasonForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DowntimeReasonInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DowntimeReasonsTable::configure($table);
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
            'index' => ListDowntimeReasons::route('/'),
            'create' => CreateDowntimeReason::route('/create'),
            'view' => ViewDowntimeReason::route('/{record}'),
            'edit' => EditDowntimeReason::route('/{record}/edit'),
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
