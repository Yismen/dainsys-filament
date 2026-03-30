<?php

namespace App\Filament\QA\Resources\Evaluations\Schemas;

use App\Enums\QuestionScorePercentage;
use App\Models\Employee;
use App\Models\Evaluation;
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
            ->columns(3)
            ->components([
                DatePicker::make('evaluation_date')
                    ->label('Record Date')
                    ->required()
                    ->default(now()),
                TextInput::make('record_number')
                    ->label('Record Number')
                    ->required()
                    ->unique(table: Evaluation::class, ignoreRecord: true),
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
                    ->live()
                    ->afterStateHydrated(function (Get $get, Set $set, ?string $state): void {
                        if (blank($state) || filled($get('questionScores'))) {
                            return;
                        }

                        $set('threshold_percentage', self::getThresholdPercentage($state));
                        $set('questionScores', self::getQuestionScoreDefaults($state));
                    })
                    ->afterStateUpdated(function (Set $set, ?string $state): void {
                        if (blank($state)) {
                            $set('threshold_percentage', null);
                            $set('questionScores', []);

                            return;
                        }

                        $set('threshold_percentage', self::getThresholdPercentage($state));
                        $set('questionScores', self::getQuestionScoreDefaults($state));
                    }),
                TextInput::make('threshold_percentage')
                    ->label('Threshold %')
                    ->disabled(),
                Textarea::make('comments')
                    ->columnSpanFull(),
                Repeater::make('questionScores')
                    ->relationship('questionScores')
                    ->label('Question Scores')
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->defaultItems(0)
                    ->required()
                    ->reorderableWithButtons(false)
                    ->reorderableWithDragAndDrop(false)
                    ->columns(2)
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
                            ->disabled()
                            ->dehydrated()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                if (! $state) {
                                    $set('max_points_snapshot', 0);

                                    return;
                                }

                                $maxPoints = (int) QAQuestion::query()->whereKey($state)->value('max_points');

                                $set('max_points_snapshot', $maxPoints);
                            })
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('max_points_snapshot')
                            ->label('Max Points')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Select::make('points_awarded')
                            ->label('Score')
                            ->options(QuestionScorePercentage::class)
                            ->placeholder('Select a score...')
                            ->required()
                            ->live(),
                        Textarea::make('evaluator_note')
                            ->required(function (Get $get): bool {
                                $value = $get('points_awarded');
                                if ($value === null) {
                                    return false;
                                }

                                $score = $value instanceof QuestionScorePercentage ? $value->value : (int) $value;

                                return $score <= 60;
                            })
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected static function getThresholdPercentage(string $qaFormId): ?float
    {
        return QAForm::query()
            ->whereKey($qaFormId)
            ->value('passing_threshold_percentage');
    }

    protected static function getQuestionScoreDefaults(string $qaFormId): array
    {
        return QAQuestion::query()
            ->where('qa_form_id', $qaFormId)
            ->orderBy('display_order')
            ->get(['id', 'max_points'])
            ->map(fn (QAQuestion $question): array => [
                'qa_question_id' => $question->id,
                'max_points_snapshot' => $question->max_points,
                'points_awarded' => null,
                'evaluator_note' => null,
            ])
            ->all();
    }
}
