<?php

use App\Models\User;

it('displays welcome page for unauthenticated users', function () {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertSee('Welcome to');
    $response->assertSee('' . config('app.name'));
    $response->assertSee('Bringing visibility and transparency to your workforce');
    $response->assertSee('Get Started');
});

it('displays login and register links for unauthenticated users', function () {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertSee('Login');
    $response->assertViewHas('__env');
});

it('displays dashboard link for authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertSuccessful();
    $response->assertSee('Go to Dashboard');
});

it('displays all feature sections', function () {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertSee('Key Features');
    $response->assertSee('Hours Tracking');
    $response->assertSee('Payroll Management');
    $response->assertSee('Incentives', false);
    $response->assertSee('Rewards', false);
    $response->assertSee('Production KPIs');
    $response->assertSee('Team Communication');
});

it('displays stats section with all metrics', function () {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertSee('100%');
    $response->assertSee('Uptime');
    $response->assertSee('24/7');
    $response->assertSee('Support');
    $response->assertSee('Enterprise-Grade');
    $response->assertSee('Fast');
});

it('displays call to action section', function () {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertSee('Ready to transform your team?');
    $response->assertSee('Join thousands of organizations already streamlining their workforce management with ' . config('app.name'));
});

it('displays footer with company links', function () {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertSee(config('app.name'));
    $response->assertSee('Company');
    $response->assertSee('Product');
    $response->assertSee('Resources');
});

it('displays proper page title', function () {
    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertViewIs('welcome');
});
