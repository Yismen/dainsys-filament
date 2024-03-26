<?php

namespace App\Filament\App\Resources\DowntimeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\App\Resources\DowntimeResource;

class CreateDowntime extends CreateRecord
{
    protected static string $resource = DowntimeResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['file'] = join('-', [
            'downtime',
            $data['campaign_id'],
            now()->format('Y-m-d')
        ]);

        return $data;
    }
}
