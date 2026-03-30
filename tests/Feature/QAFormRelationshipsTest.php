<?php

use App\Enums\QARoles;
use App\Models\Evaluation;
use App\Models\QAForm;
use App\Models\QAQuestion;
use App\Models\Role;

it('loads qa questions and evaluations through qa_form_id relations', function (): void {
    Role::firstOrCreate(['name' => QARoles::Manager->value], ['guard_name' => 'web']);

    $qaForm = QAForm::factory()->create();

    $question = QAQuestion::factory()->create([
        'qa_form_id' => $qaForm->id,
    ]);

    $evaluation = Evaluation::factory()->create([
        'qa_form_id' => $qaForm->id,
    ]);

    expect($qaForm->questions()->pluck('id')->all())
        ->toBe([$question->id])
        ->and($qaForm->evaluations()->pluck('id')->all())
        ->toBe([$evaluation->id]);
});
