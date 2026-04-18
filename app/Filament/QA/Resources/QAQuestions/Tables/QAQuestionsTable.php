<?php

namespace App\Filament\QA\Resources\QAQuestions\Tables;

use App\Models\QAForm;
use App\Services\ModelListService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class QAQuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('display_order')
            ->columns([
                TextColumn::make('form.name')
                    ->label(__('filament.qa_form'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('display_order')
                    ->label(__('filament.display_order'))
                    ->sortable(),
                TextColumn::make('text')
                    ->label(__('filament.text'))
                    ->limit(80)
                    ->searchable(),
                TextColumn::make('max_points')
                    ->label(__('filament.max_points'))
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('filament.is_active')),
                TextColumn::make('author.name')
                    ->label(__('filament.author'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('qa_form_id')
                    ->label(__('filament.qa_form'))
                    ->options(ModelListService::make(QAForm::query()))
                    ->searchable(),
                SelectFilter::make('is_active')
                    ->label(__('filament.status'))
                    ->options([
                        '1' => __('filament.filters.active'),
                        '0' => __('filament.filters.inactive'),
                    ]),
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
