<div class="relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">My Tickets</h1>
            <p class="mt-2 text-slate-600 dark:text-slate-400">Manage your tickets</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
            {{ $this->table }}
        </div>
    </div>

    <x-filament-actions::modals />
</div>
