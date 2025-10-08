<?php

namespace App\Filament\App\Resources;

use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use App\Filament\App\Resources\EmployeeResource\Pages\ListEmployees;
use App\Filament\App\Resources\EmployeeResource\Pages\CreateEmployee;
use App\Filament\App\Resources\EmployeeResource\Pages\ViewEmployee;
use App\Filament\App\Resources\EmployeeResource\Pages\EditEmployee;
use Filament\Tables;
use App\Models\Employee;
use Filament\Tables\Table;
use App\Enums\EmployeeStatus;
use Filament\Resources\Resource;
use pxlrbt\FilamentExcel\Columns\Column;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use App\Filament\Support\Forms\EmployeeSchema;
use App\Filament\Traits\HumanResourceSupportMenu;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\EmployeeResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\App\Resources\EmployeeResource\RelationManagers\LoginNamesRelationManager;
use App\Filament\App\Resources\EmployeeResource\RelationManagers\SuspensionsRelationManager;
use App\Filament\App\Resources\InformationResource\RelationManagers\InformationRelationManager;

class EmployeeResource extends Resource
{
    use HumanResourceSupportMenu;

    protected static ?string $model = Employee::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->columns(2)
                    ->schema(EmployeeSchema::toArray())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    ImageColumn::make('information.photo_url')
                        ->grow(false)
                        ->circular(),
                    TextColumn::make('full_name')
                        ->searchable(),
                    TextColumn::make('cellphone')
                        ->icon('heroicon-o-phone')
                        ->searchable(),
                    TextColumn::make('site.name')
                        ->sortable(),
                    TextColumn::make('project.name')
                        ->sortable(),
                    TextColumn::make('position.details')
                        ->sortable(),
                    TextColumn::make('status')
                        ->badge()
                        ->searchable(),
                ]),
                Panel::make([
                    TextColumn::make('personal_id')
                        ->formatStateUsing(fn ($state) => 'Personal ID: ' . $state)
                        ->searchable(),
                    TextColumn::make('hired_at')
                        ->date()
                        ->formatStateUsing(fn ($state) => 'Hired At: ' . $state)
                        ->sortable(),
                    TextColumn::make('date_of_birth')
                        ->date()
                        ->formatStateUsing(fn ($state) => 'Birthday: ' . $state->format('M-d'))
                        ->sortable(),
                    TextColumn::make('citizenship.name')
                        ->formatStateUsing(fn ($state) => 'Nationality: ' . $state)
                        ->sortable(),
                    TextColumn::make('supervisor.name')
                        ->formatStateUsing(fn ($state) => 'Supervisor: ' . $state)
                        ->sortable(),
                    TextColumn::make('afp.name')
                        ->formatStateUsing(fn ($state) => 'AFP: ' . $state)
                        ->sortable(),
                    TextColumn::make('ars.name')
                        ->formatStateUsing(fn ($state) => 'ARS: ' . $state)
                        ->sortable(),
                    TextColumn::make('marriage')
                        ->formatStateUsing(fn ($state) => 'Marital Status: ' . $state->value)
                        ->searchable(),
                    TextColumn::make('gender')
                        ->formatStateUsing(fn ($state) => 'Gender: ' . $state->value)
                        ->searchable(),
                    IconColumn::make('kids')
                        // ->formatStateUsing(fn ($state) => 'Has Kids: ' . $state)
                        ->boolean(),
                    TextColumn::make('punch')
                        ->formatStateUsing(fn ($state) => 'Punch: ' . $state)
                        ->searchable(),
                    TextColumn::make('created_at')
                        ->formatStateUsing(fn ($state) => 'Created Ar: ' . $state)
                        ->dateTime()
                        ->sortable(),
                    TextColumn::make('updated_at')
                        ->formatStateUsing(fn ($state) => 'Last Update: ' . $state)
                        ->dateTime()
                        ->sortable(),
                ])->collapsible()
                    ->collapsed(true)
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options(EmployeeStatus::toArray()),
                SelectFilter::make('site')
                    ->relationship('site', 'name'),
                SelectFilter::make('project')
                    ->relationship('project', 'name'),
                SelectFilter::make('position')
                    ->relationship('position', 'name'),
                // Tables\Filters\SelectFilter::make('department')
                //     ->relationship('department', 'name'),
            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns(4)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                //     Tables\Actions\ForceDeleteBulkAction::make(),
                //     Tables\Actions\RestoreBulkAction::make(),
                // ]),
                ExportBulkAction::make()->exports([
                    ExcelExport::make()->withColumns([
                        Column::make('id'),
                        Column::make('full_name'),
                        Column::make('personal_id'),
                        Column::make('hired_at'),
                        Column::make('date_of_birth'),
                        Column::make('cellphone'),
                        Column::make('status'),
                        Column::make('marriage'),
                        Column::make('gender'),
                        Column::make('kids'),
                        Column::make('punch'),
                        Column::make('site.name')
                            ->heading('Site'),
                        Column::make('project.name')
                            ->heading('Project'),
                        Column::make('position.name')
                            ->heading('Position'),
                        Column::make('citizenship.name')
                            ->heading('Citizenship'),
                        Column::make('supervisor.name')
                            ->heading('Supervisor'),
                        Column::make('afp.name')
                            ->heading('Afp'),
                        Column::make('ars.name')
                            ->heading('Ars'),
                    ])
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            InformationRelationManager::class,
            LoginNamesRelationManager::class,
            SuspensionsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'view' => ViewEmployee::route('/{record}'),
            'edit' => EditEmployee::route('/{record}/edit'),
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
