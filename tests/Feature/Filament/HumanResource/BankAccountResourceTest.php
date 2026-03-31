<?php

use App\Filament\HumanResource\Resources\BankAccounts\Pages\ManageBankAccounts;
use App\Models\Bank;
use App\Models\BankAccount;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );
});

test('can filter bank accounts by bank', function (): void {
    $firstBank = Bank::factory()->create();
    $secondBank = Bank::factory()->create();

    $firstBankAccount = BankAccount::factory()->for($firstBank)->create();
    $secondBankAccount = BankAccount::factory()->for($secondBank)->create();

    actingAs($this->createUserWithPermissionTo('view-any BankAccount'));

    livewire(ManageBankAccounts::class)
        ->filterTable('bank_id', (string) $firstBank->id)
        ->assertCanSeeTableRecords([$firstBankAccount])
        ->assertCanNotSeeTableRecords([$secondBankAccount]);
});
