<x-filament-panels::page>
    <div class="flex flex-col h-full w-full relative pb-28">
        <x-filament-actions::modals />

        @if (count($this->selectedEmployees))
            <div class="fixed bottom-12 right-6 z-50 flex items-center gap-4 rounded-xl bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border border-white/20 dark:border-white/10 p-4 shadow-xl">
                <div class="flex flex-col w-full">
                    {{ $this->reasignSelectedEmployeesAction }}
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ count($selectedEmployees) }} employee(s) selected
                    </span>
                </div>
            </div>
        @endif

        @include('filament.workforce.pages.__active-supervisors')

        @include('filament.workforce.pages.__inactive-supervisors')

        @include('filament.workforce.pages.__employees-without-supervisor')
    </div>
</x-filament-panels::page>
