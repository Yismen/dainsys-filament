<?php

namespace App\Filament\QA\Resources\QAForms\Pages;

use App\Filament\QA\Resources\QAForms\QAFormResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateQAForm extends CreateRecord
{
    protected static string $resource = QAFormResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();

        return $data;
    }
}
