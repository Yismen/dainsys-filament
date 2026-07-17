<?php

namespace App\Filament\QA\Resources\QAQuestions\Schemas;

use App\Models\QAForm;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QAQuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('qa_form_id')
                    ->label(__('filament.qa_form'))
                    ->options(ModelListService::make(QAForm::query()))
                    ->searchable()
                    ->required(),
                TextInput::make('display_order')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->minValue(1),
                Textarea::make('text')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('max_points')
                    ->numeric()
                    ->required()
                    ->minValue(1),
                Toggle::make('is_active')
                    ->default(true),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
