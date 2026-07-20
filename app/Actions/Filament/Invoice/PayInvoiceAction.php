<?php

namespace App\Actions\Filament\Invoice;

use App\Models\Invoice;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class PayInvoiceAction
{
    public static function make(): Action
    {
        return Action::make('Pay')
            ->visible(fn ($record) => $record->balance_pending > 0)
            ->color(Color::Purple)
            ->icon(Heroicon::OutlinedCreditCard)
            ->button()
            ->schema([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('amount')
                            ->label(__('filament.amount'))
                            ->numeric()
                            ->required()
                            ->default(fn ($record) => $record->balance_pending)
                            ->maxValue(fn ($record) => $record->balance_pending)
                            ->helperText(fn ($record) => __('filament.max_amount', ['amount' => number_format($record->balance_pending, 2)])),
                        DatePicker::make('date')
                            ->label(__('filament.date'))
                            ->default(now())
                            // ->minDate(fn ($record) => $record->date)
                            // ->maxDate(now())
                            ->required(),
                        Textarea::make('reference')
                            ->label(__('filament.reference'))
                            ->columnSpanFull()
                            ->maxLength(255),
                        FileUpload::make('images')
                            ->label(__('filament.payment_proof_images'))
                            ->image()
                            ->multiple()
                            ->columnSpanFull()
                            ->maxFiles(5)
                            ->directory('invoice-payments')
                            ->visibility('private'),
                    ]),
            ])
            ->action(function (array $data, Invoice $record): void {
                $record->payments()->create($data);
            });
    }
}
