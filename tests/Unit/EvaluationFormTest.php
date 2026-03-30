<?php

use App\Filament\QA\Resources\Evaluations\Schemas\EvaluationForm;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('does not allow adding, deleting, or reordering question score rows in the evaluation form repeater', function (): void {
    $schema = EvaluationForm::configure(Schema::make());

    $repeater = collect($schema->getComponents())
        ->first(fn (mixed $component): bool => $component instanceof Repeater && $component->getName() === 'questionScores');

    expect($repeater)->toBeInstanceOf(Repeater::class)
        ->and($repeater->isAddable())->toBeFalse()
        ->and($repeater->isDeletable())->toBeFalse()
        ->and($repeater->isReorderable())->toBeFalse()
        ->and($repeater->isReorderableWithButtons())->toBeFalse()
        ->and($repeater->isReorderableWithDragAndDrop())->toBeFalse();
});
