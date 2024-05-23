<?php

namespace App\Filament\App\Resources;

use Filament\Tables;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\EmployeeStatus;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    Tables\Columns\ImageColumn::make('information.photo_url')
                        ->grow(false)
                        ->circular(),
                    Tables\Columns\TextColumn::make('full_name')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('cellphone')
                        ->icon('heroicon-o-phone')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('site.name')
                        ->sortable(),
                    Tables\Columns\TextColumn::make('project.name')
                        ->sortable(),
                    Tables\Columns\TextColumn::make('position.details')
                        ->sortable(),
                    Tables\Columns\TextColumn::make('status')
                        ->badge()
                        ->searchable(),
                ]),
                Panel::make([
                    Tables\Columns\TextColumn::make('personal_id')
                        ->formatStateUsing(fn ($state) => 'Personal ID: ' . $state)
                        ->searchable(),
                    Tables\Columns\TextColumn::make('hired_at')
                        ->date()
                        ->formatStateUsing(fn ($state) => 'Hired At: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('date_of_birth')
                        ->date()
                        ->formatStateUsing(fn ($state) => 'Birthday: ' . $state->format('M-d'))
                        ->sortable(),
                    Tables\Columns\TextColumn::make('citizenship.name')
                        ->formatStateUsing(fn ($state) => 'Nationality: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('supervisor.name')
                        ->formatStateUsing(fn ($state) => 'Supervisor: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('afp.name')
                        ->formatStateUsing(fn ($state) => 'AFP: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('ars.name')
                        ->formatStateUsing(fn ($state) => 'ARS: ' . $state)
                        ->sortable(),
                    Tables\Columns\TextColumn::make('marriage')
                        ->formatStateUsing(fn ($state) => 'Marital Status: ' . $state->value)
                        ->searchable(),
                    Tables\Columns\TextColumn::make('gender')
                        ->formatStateUsing(fn ($state) => 'Gender: ' . $state->value)
                        ->searchable(),
                    Tables\Columns\IconColumn::make('kids')
                        // ->formatStateUsing(fn ($state) => 'Has Kids: ' . $state)
                        ->boolean(),
                    Tables\Columns\TextColumn::make('punch')
                        ->formatStateUsing(fn ($state) => 'Punch: ' . $state)
                        ->searchable(),
                    Tables\Columns\TextColumn::make('created_at')
                        ->formatStateUsing(fn ($state) => 'Created Ar: ' . $state)
                        ->dateTime()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('updated_at')
                        ->formatStateUsing(fn ($state) => 'Last Update: ' . $state)
                        ->dateTime()
                        ->sortable(),
                ])->collapsible()
                    ->collapsed(true)
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')
                    ->options(EmployeeStatus::toArray()),
                Tables\Filters\SelectFilter::make('site')
                    ->relationship('site', 'name'),
                Tables\Filters\SelectFilter::make('project')
                    ->relationship('project', 'name'),
                Tables\Filters\SelectFilter::make('position')
                    ->relationship('position', 'name'),
                // Tables\Filters\SelectFilter::make('department')
                //     ->relationship('department', 'name'),
            ], layout: \Filament\Tables\Enums\FiltersLayout::Modal)
            ->filtersFormColumns(4)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
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
