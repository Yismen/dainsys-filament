@if ($this->activeSupervisors->count())
    {{-- Supervisors with Employees --}}
    <h3 class="text-blue-600 dark:text-blue-400 mb-2">Active Supervisors</h3>
    <div class="grid gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-3 items-stretch ">
        @foreach ($this->activeSupervisors as $supervisor)
            <div class="group flex flex-col h-80 bg-white dark:bg-gray-800 rounded-xl border border-gray-200/70 dark:border-gray-700/70 p-4 shadow-sm transition-transform duration-200 hover:-translate-y-1 hover:shadow-lg" wire:key="supervisor-{{ $supervisor->id }}">
                <div class="flex flex-col items-start justify-between gap-3 pb-3">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white w-full flex items-center justify-between">
                        {{ $supervisor->name }}
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $supervisor->employees->count() }} employees)</span>
                        {{ ($this->deactivateSupervisorAction)(['supervisor' => $supervisor->id]) }}
                    </h3>

                    <livewire:selectable-employees :employees="$supervisor->employees" wire:key="selectable-employees-{{ $supervisor->id }}"/>
                </div>

            </div>
        @endforeach
    </div>
@endif
