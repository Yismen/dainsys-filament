@if ($this->activeSupervisors->count())
    {{-- Supervisors with Employees --}}
    <h3 class="text-blue-600 dark:text-blue-400 mb-2">Active Supervisors</h3>
    <div class="grid gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-3 items-stretch ">
        @foreach ($this->activeSupervisors as $supervisor)
            @include('filament.workforce.pages.__supervisor-card', [
                'supervisor' => $supervisor,
                'toggleAction' => ($this->deactivateSupervisorAction)(['supervisor' => $supervisor->id]),
                'editUserAction' => ($this->editSupervisorUserAction)(['supervisor' => $supervisor->id]),
            ])
        @endforeach
    </div>
@endif
