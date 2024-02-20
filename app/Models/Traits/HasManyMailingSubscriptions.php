<?php

namespace App\Models\Traits;

use App\Models\MailingSubscription;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyMailingSubscriptions
{
    public function mailingSubscriptions(): HasMany
    {
        return $this->hasMany(MailingSubscription::class);
    }
}
