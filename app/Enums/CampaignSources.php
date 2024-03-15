<?php

namespace App\Enums;

use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumValues;
use App\Enums\Traits\EnumToArray;
use App\Enums\Contracts\EnumContract;

enum CampaignSources: string implements EnumContract
{
    use EnumNames, EnumValues, EnumToArray;

    case Chat = 'Chat';
    case Email = 'Email';
    case Inbound = 'Inbound';
    case Outbound = 'Outbound';
    case QAReview = 'QAReview';
    case Resubmissions = 'Resubmissions';
    case Training = 'Training';
}
