<?php

use App\Models\User;

it('displays welcome page for unauthenticated users', function (): void {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertSee(''.config('app.name'));
    $response->assertSee('Get Started');
});

it('displays login and register links for unauthenticated users', function (): void {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertSee('Login');
    $response->assertViewHas('__env');
});

it('displays dashboard link for authenticated users', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertSuccessful();
    $response->assertSee('Go to Dashboard');
});

it('displays footer with company links', function (): void {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertSee(config('app.name'));
    $response->assertSee('Company');
    $response->assertSee('Product');
    $response->assertSee('Resources');
});

it('displays proper page title', function (): void {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertViewIs('welcome');
});
