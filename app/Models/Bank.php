<?php

namespace App\Models;

use App\Models\Traits\HasManyBankAccounts;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends \App\Models\BaseModels\AppModel
{
    use HasManyBankAccounts;
    use SoftDeletes;

    protected $fillable = ['name', 'person_of_contact', 'phone', 'email', 'description'];

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Employee::class,
            BankAccount::class,
            'bank_id', // Foreign key on BankAccounts table...
            'id', // Foreign key on Employees table...
            'id', // Local key on Banks table...
            'employee_id' // Local key on BankAccounts table...
        );
    }
}
