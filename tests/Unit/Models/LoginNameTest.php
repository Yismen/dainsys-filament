<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\LoginName;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginNameTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_names_model_interacts_with_db_table()
    {
        Mail::fake();
        $data = LoginName::factory()->make();

        LoginName::create($data->toArray());

        $this->assertDatabaseHas('login_names', $data->only([
            'login_name', 'employee_id'
        ]));
    }

    /** @test */
    public function login_name_model_uses_soft_delete()
    {
        Mail::fake();
        $login_name = LoginName::factory()->create();

        $login_name->delete();

        $this->assertSoftDeleted(LoginName::class, $login_name->only(['id']));
    }

    /** @test */
    public function login_names_model_belongs_to_one_employee()
    {
        Mail::fake();
        $login_name = LoginName::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $login_name->employee());
    }
}
