<?php

namespace App\Filament\App\Resources\DowntimeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\DowntimeResource;

class EditDowntime extends EditRecord
{
    protected static string $resource = DowntimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['file'] = join('-', [
            'downtime',
            $data['campaign_id'],
            now()->format('Y-m-d')
        ]);

        return $data;
    }
}
