<?php

namespace App\Models;

use App\Models\BaseModels\AppModel;
use App\Models\Traits\HasHiredEmployees;
use App\Models\Traits\HasManyBankAccounts;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'person_of_contact', 'phone', 'email', 'description'])]
class Bank extends AppModel
{
    use HasHiredEmployees;
    use HasManyBankAccounts;
    use SoftDeletes;

    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(
            Employee::class,
            BankAccount::class,
            'bank_id',
            'id',
            'id',
            'employee_id'
        );
    }
}
