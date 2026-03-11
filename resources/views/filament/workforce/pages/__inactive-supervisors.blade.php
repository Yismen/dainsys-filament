@if ($this->inactiveSupervisors->count())
    {{-- Inactive Supervisors with Employees --}}
    <h3 class="text-red-600 dark:text-red-400 mb-2">Inactive Supervisors</h3>
    <div class="grid gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-3 items-stretch ">
        @foreach ($this->inactiveSupervisors as $supervisor)
            @include('filament.workforce.pages.__supervisor-card', [
                'supervisor' => $supervisor,
                'toggleAction' => ($this->reactivateSupervisorAction)(['supervisor' => $supervisor->id]),
                'editUserAction' => ($this->editSupervisorUserAction)(['supervisor' => $supervisor->id]),
            ])
        @endforeach
    </div>
@endif
