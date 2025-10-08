<?php

namespace App\Filament\App\Resources\DowntimeResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Resources\DowntimeResource;

class EditDowntime extends EditRecord
{
    protected static string $resource = DowntimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
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
