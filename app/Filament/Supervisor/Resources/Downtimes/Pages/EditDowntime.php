<?php

namespace App\Filament\Supervisor\Resources\Downtimes\Pages;

use App\Enums\DowntimeStatuses;
use App\Filament\Supervisor\Resources\Downtimes\DowntimeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDowntime extends EditRecord
{
    protected static string $resource = DowntimeResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        // Redirect to view if not pending
        if ($this->record->status !== DowntimeStatuses::Pending) {
            $this->redirect(ViewDowntime::getUrl(['record' => $this->record]));
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
