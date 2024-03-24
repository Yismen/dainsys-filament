<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BankAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function bank_accounts_model_interacts_with_db_table()
    {
        Mail::fake();
        $data = BankAccount::factory()->make();

        BankAccount::create($data->toArray());

        $this->assertDatabaseHas('bank_accounts', $data->only([
            'employee_id', 'bank_id', 'account'
        ]));
    }

    /** @test */
    public function bank_account_model_uses_soft_delete()
    {
        Mail::fake();
        $bank_account = BankAccount::factory()->create();

        $bank_account->delete();

        $this->assertSoftDeleted(BankAccount::class, $bank_account->only(['id']));
    }

    /** @test */
    public function bank_accounts_model_belongs_to_employee()
    {
        Mail::fake();
        $bank_account = BankAccount::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $bank_account->employee());
    }

    /** @test */
    public function bank_accounts_model_belongs_to_bank()
    {
        Mail::fake();
        $bank_account = BankAccount::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $bank_account->bank());
    }
}
