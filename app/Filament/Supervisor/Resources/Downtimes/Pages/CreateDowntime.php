<?php

namespace App\Filament\Supervisor\Resources\Downtimes\Pages;

use App\Filament\Supervisor\Resources\Downtimes\DowntimeResource;
use App\Models\Comment;
use Filament\Resources\Pages\CreateRecord;

class CreateDowntime extends CreateRecord
{
    protected static string $resource = DowntimeResource::class;

    protected function afterCreate(): void
    {
        $state = $this->form->getState();
        $comment = $state['request_comment'] ?? null;

        if (! empty($comment)) {
            Comment::query()->forceCreate([
                'text' => $comment,
                'commentable_id' => $this->record->id,
                'commentable_type' => \App\Models\Downtime::class,
            ]);
        }
    }
}
