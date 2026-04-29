<?php

namespace App\Filament\Recruitment\Resources\Applicants;

use App\Filament\Recruitment\Enums\RecruitmentNavigationEnum;
use App\Filament\Recruitment\Resources\Applicants\Pages\ListApplicants;
use App\Filament\Recruitment\Resources\Applicants\Schemas\ApplicantForm;
use App\Filament\Recruitment\Resources\Applicants\Schemas\ApplicantInfolist;
use App\Filament\Recruitment\Resources\Applicants\Tables\ApplicantsTable;
use App\Models\Applicant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ApplicantResource extends Resource
{
    protected static ?string $model = Applicant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static UnitEnum|string|null $navigationGroup = RecruitmentNavigationEnum::Recruitment;

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components(ApplicantForm::schema());
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components(ApplicantInfolist::schema());
    }

    public static function table(Table $table): Table
    {
        return ApplicantsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApplicants::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
