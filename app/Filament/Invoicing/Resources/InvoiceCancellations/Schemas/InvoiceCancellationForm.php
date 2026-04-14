<?php

namespace App\Filament\Invoicing\Resources\InvoiceCancellations\Schemas;

use App\Models\Invoice;
use App\Services\ModelListService;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class InvoiceCancellationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('invoice_id')
                    ->label(__('Invoice'))
                    ->options(ModelListService::make(Invoice::query()->where('total_paid', 0), 'id', 'number'))
                    ->required()
                    ->searchable()
                    ->live(),
                DatePicker::make('date')
                    ->label(__('Cancellation date'))
                    ->default(now())
                    ->minDate(static fn (Get $get): ?string => Invoice::query()->whereKey($get('invoice_id'))->value('date'))
                    ->maxDate(now())
                    ->rule(static function (Get $get): Closure {
                        return static function (string $attribute, mixed $value, Closure $fail) use ($get): void {
                            if (blank($value)) {
                                return;
                            }

                            $invoiceDate = Invoice::query()->whereKey($get('invoice_id'))->value('date');

                            if ($invoiceDate === null) {
                                return;
                            }

                            if (Carbon::parse((string) $value)->lt(Carbon::parse((string) $invoiceDate))) {
                                $fail(__('Cancellation date must be equal to or after invoice date.'));
                            }
                        };
                    })
                    ->required(),
                TextInput::make('reason')
                    ->label(__('Reason'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->label(__('Notes'))
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
