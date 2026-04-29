<?php

namespace App\Filament\Recruitment\Resources\Applications;

use App\Filament\Recruitment\Enums\RecruitmentNavigationEnum;
use App\Filament\Recruitment\Resources\Applications\Pages\ListApplications;
use App\Filament\Recruitment\Resources\Applications\Schemas\ApplicationForm;
use App\Filament\Recruitment\Resources\Applications\Schemas\ApplicationInfolist;
use App\Filament\Recruitment\Resources\Applications\Tables\ApplicationsTable;
use App\Models\Application;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static UnitEnum|string|null $navigationGroup = RecruitmentNavigationEnum::Recruitment;

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema->components(ApplicationForm::schema());
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components(ApplicationInfolist::schema());
    }

    public static function table(Table $table): Table
    {
        return ApplicationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApplications::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['applicant', 'jobOpening'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
