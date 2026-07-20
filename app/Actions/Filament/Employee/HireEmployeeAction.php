<?php

namespace App\Actions\Filament\Employee;

use App\Models\Employee;
use App\Schemas\Filament\HumanResource\HireEmployeeSchema;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Support\Colors\Color;
use Illuminate\Support\Arr;

class HireEmployeeAction
{
    public static function make(string $name = 'hire'): Action
    {
        return
            Action::make($name)
                ->visible(fn (Employee $record) => $record->canBeHired())
                ->color(Color::Green)
                ->modalHeading(__('filament.hire_employee'))
                ->schema([
                    Grid::make()
                        ->columns(2)
                        ->schema(HireEmployeeSchema::make(isBeingHired: true)),
                ])->action(function (Employee $record, array $data, $livewire): void {
                    $data['date'] = $data['date'] ?? $data['hired_at']; // On the form, we have 'hired_at' field, but for the hires relationship, we need to save it as 'date'

                    $record->hires()->create(Arr::except($data, ['internal_id', 'hired_at']));

                    $record->internal_id = $data['internal_id'] ?? null;
                    $record->saveQuietly();

                    $record->refresh();

                    Notification::make()
                        ->success()
                        ->body(__('filament.employee_hired_body', ['name' => $record->full_name]))
                        ->send();

                    $parentActionName = $livewire->getMountedAction(0)?->getName();

                    if (in_array($parentActionName, ['edit', 'view'], true)) {
                        $livewire->replaceMountedAction($parentActionName, context: [
                            'table' => true,
                            'recordKey' => (string) $record->getKey(),
                        ]);
                    }
                });
    }
}
