<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;

enum CampaignSources: string implements EnumContract
{
    use EnumNames, EnumToArray, EnumValues;

    case Chat = 'Chat';
    case Email = 'Email';
    case Inbound = 'Inbound';
    case Outbound = 'Outbound';
    case QAReview = 'QAReview';
    case Resubmissions = 'Resubmissions';
    case Training = 'Training';
}
