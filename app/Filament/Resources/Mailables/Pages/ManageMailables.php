<?php

namespace App\Filament\Resources\Mailables\Pages;

use App\Filament\Resources\Mailables\MailableResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Artisan;

class ManageMailables extends ManageRecords
{
    protected static string $resource = MailableResource::class;

    public function mount(): void
    {
        Artisan::call('dainsys:sync-mailables-table');
    }

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
            Action::make('Sync Mailables')
                ->icon(Heroicon::OutlinedCircleStack)
                ->color(Color::Teal)
                ->successNotificationTitle('Mailables Synced')
                ->action(function (): void {
                    Artisan::call('dainsys:sync-mailables-table');
                }),
        ];
    }
}
