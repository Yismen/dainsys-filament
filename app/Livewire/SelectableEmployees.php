<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class SelectableEmployees extends Component
{
    public array|Collection $employees;

    public bool $allSelected = false;

    public array $selectedEmployees = [];

    public array $reportableEmployees = [];

    public function render()
    {
        return view('livewire.selectable-employees');
    }

    public function mount(Collection $employees) {}

    #[On('employeesReassigned')]
    public function employeesReassigned(): void
    {
        // Reset selected employees after reassignment
        $this->selectedEmployees = [];
        $this->allSelected = false;
    }

    public function updatedSelectedEmployees()
    {
        $this->allSelected = count($this->selectedEmployees) === count($this->employees);

        $this->reportableEmployees = array_map(function ($employee) {
            $selected = in_array($employee['id'], $this->selectedEmployees);

            return ['id' => $employee['id'], 'name' => $employee['full_name'], 'selected' => $selected];
        }, $this->employees->toArray());

        $this->dispatch('employeesSelected', $this->reportableEmployees);
    }

    public function toggleSelectAll(): void
    {
        if ($this->allSelected) {
            $this->selectedEmployees = [];
            $this->allSelected = false;
        } else {
            $this->selectedEmployees = $this->employees->pluck('id')->toArray();
            $this->allSelected = true;
        }

        $this->updatedSelectedEmployees();
    }
}
