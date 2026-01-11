<?php

namespace App\Filament\Workforce\Resources\Productions\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ImportAction;
use Filament\Support\Colors\Color;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use App\Filament\Imports\ProductionImporter;
use Filament\Support\Icons\Heroicon;

class ProductionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make()
                    ->importer(ProductionImporter::class)
                    ->color(Color::Indigo)
                    ->icon(Heroicon::ArrowUpTray)
            ])
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('campaign.name')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('revenue_type')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('supervisor.name')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('revenue_rate')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sph_goal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('conversions')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_time')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('production_time')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('talk_time')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('billable_time')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('revenue')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('converted_to_payroll_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
