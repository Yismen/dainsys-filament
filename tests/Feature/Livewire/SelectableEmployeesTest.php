<?php

use App\Livewire\SelectableEmployees;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(SelectableEmployees::class)
        ->assertStatus(200);
});
