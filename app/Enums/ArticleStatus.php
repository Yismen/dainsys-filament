<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;

enum ArticleStatus: string implements EnumContract
{
    use EnumNames;
    use EnumToArray;
    use EnumValues;

    case Draft = 'draft';
    case Published = 'published';

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'warning',
            self::Published => 'success',
        };
    }
}
