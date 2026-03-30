<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum QuestionScorePercentage: int implements HasColor, HasLabel
{
    case NotDemonstrated = 0;
    case Unacceptable = 20;
    case NeedsImprovement = 40;
    case Developing = 60;
    case Proficient = 80;
    case Exemplary = 100;

    public function getLabel(): string
    {
        return match ($this) {
            self::NotDemonstrated => '0% — Not Demonstrated',
            self::Unacceptable => '20% — Unacceptable',
            self::NeedsImprovement => '40% — Needs Improvement',
            self::Developing => '60% — Developing',
            self::Proficient => '80% — Proficient',
            self::Exemplary => '100% — Exemplary',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NotDemonstrated, self::Unacceptable => 'danger',
            self::NeedsImprovement, self::Developing => 'warning',
            self::Proficient, self::Exemplary => 'success',
        };
    }
}
