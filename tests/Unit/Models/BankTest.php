<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Bank;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BankTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function banks_model_interacts_with_db_table()
    {
        $data = Bank::factory()->make();

        Bank::create($data->toArray());

        $this->assertDatabaseHas('banks', $data->only([
            'name', 'description'
        ]));
    }

    /** @test */
    public function bank_model_uses_soft_delete()
    {
        $bank = Bank::factory()->create();

        $bank->delete();

        $this->assertSoftDeleted(Bank::class, $bank->toArray());
    }

    /** @test */
    public function banks_model_morph_one_information()
    {
        $bank = Bank::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class, $bank->information());
    }

    /** @test */
    public function banks_model_has_many_employees()
    {
        $bank = Bank::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $bank->employees());
    }
}
