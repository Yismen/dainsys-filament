<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasLabel;

enum ArticleStatus: string implements EnumContract, HasLabel
{
    use EnumNames;
    use EnumToArray;
    use EnumValues;

    case Draft = 'draft';
    case Published = 'published';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => __('enums.article_status.draft'),
            self::Published => __('enums.article_status.published'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'warning',
            self::Published => 'success',
        };
    }
}
