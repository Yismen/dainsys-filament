<?php

namespace App\Models\Traits;

use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasOneBankAccount
{
    public function bankAccount(): HasOne
    {
        return $this->hasOne(BankAccount::class);
    }
}
