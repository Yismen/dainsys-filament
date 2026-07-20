<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasLabel;

enum CampaignSources: string implements EnumContract, HasLabel
{
    use EnumNames, EnumToArray, EnumValues;

    case Chat = 'Chat';
    case Email = 'Email';
    case Inbound = 'Inbound';
    case Outbound = 'Outbound';
    case QAReview = 'QA Review';
    case Resubmissions = 'Resubmissions';
    case Training = 'Training';

    public function getLabel(): string
    {
        return match ($this) {
            self::Chat => __('enums.campaign_source.chat'),
            self::Email => __('enums.campaign_source.email'),
            self::Inbound => __('enums.campaign_source.inbound'),
            self::Outbound => __('enums.campaign_source.outbound'),
            self::QAReview => __('enums.campaign_source.qa_review'),
            self::Resubmissions => __('enums.campaign_source.resubmissions'),
            self::Training => __('enums.campaign_source.training'),
        };
    }
}
