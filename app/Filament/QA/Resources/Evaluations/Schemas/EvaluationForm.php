<?php

namespace App\Filament\QA\Resources\Evaluations\Schemas;

use App\Models\Employee;
use App\Models\QAForm;
use App\Models\QAQuestion;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class EvaluationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('evaluation_date')
                    ->required()
                    ->default(now()),
                Select::make('employee_id')
                    ->label('Employee')
                    ->options(ModelListService::make(Employee::query(), value_field: 'full_name'))
                    ->searchable()
                    ->required(),
                Select::make('qa_form_id')
                    ->label('QA Form')
                    ->options(ModelListService::make(QAForm::query(), value_field: 'name'))
                    ->searchable()
                    ->required()
                    ->live(),
                TextInput::make('threshold_percentage')
                    ->label('Threshold %')
                    ->disabled(),
                Textarea::make('comments')
                    ->columnSpanFull(),
                Repeater::make('questionScores')
                    ->relationship('questionScores')
                    ->label('Question Scores')
                    ->schema([
                        Select::make('qa_question_id')
                            ->label('Question')
                            ->options(function (Get $get): array {
                                $qaFormId = $get('../../qa_form_id');

                                if (! $qaFormId) {
                                    return [];
                                }

                                return QAQuestion::query()
                                    ->where('qa_form_id', $qaFormId)
                                    ->orderBy('display_order')
                                    ->pluck('text', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                if (! $state) {
                                    $set('max_points_snapshot', 0);

                                    return;
                                }

                                $maxPoints = (int) QAQuestion::query()->whereKey($state)->value('max_points');

                                $set('max_points_snapshot', $maxPoints);
                            })
                            ->required(),
                        TextInput::make('max_points_snapshot')
                            ->label('Max Points')
                            ->numeric()
                            ->required(),
                        TextInput::make('points_awarded')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(fn (Get $get): int => max(0, (int) $get('max_points_snapshot'))),
                        Textarea::make('evaluator_note'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
