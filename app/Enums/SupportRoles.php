<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasLabel;

enum SupportRoles: string implements EnumContract, HasLabel
{
    use EnumNames;
    use EnumToArray;
    use EnumValues;

    case Manager = 'Support Manager';
    case Agent = 'Support Agent';

    public function getLabel(): string
    {
        return match ($this) {
            self::Manager => __('enums.support_role.manager'),
            self::Agent => __('enums.support_role.agent'),
        };
    }
}
