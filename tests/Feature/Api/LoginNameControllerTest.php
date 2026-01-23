<?php

use App\Models\User;
use App\Models\LoginName;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

it('protects the route against unauthorized tokens', function () {
    Sanctum::actingAs(User::factory()->create());

    $response = $this->get('/api/login_names');

    $response->assertForbidden();
});

it('returns correct structure', function () {

    LoginName::factory()->create();
    Sanctum::actingAs(user: User::factory()->create(), abilities:['use-dainsys']);

    $response = $this->get('/api/login_names');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'login_name',
                    'employee_id',
                    'employee_full_name',
                ],
            ],
        ]);
});
