<?php

namespace App\Models\Traits;

use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyBankAccounts
{
    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }
}
