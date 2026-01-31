<x-filament-panels::page>
    <x-filament::section>
        <div class="max-w-2xl">
            <h2 class="text-lg font-semibold mb-4">Update Your Password</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                You are required to update your password. Please enter your current password and choose a new one.
            </p>

            <form wire:submit="save" class="space-y-6">
                {{ $this->form }}

                <div class="flex gap-3">
                    <x-filament::button type="submit">
                        Update Password
                    </x-filament::button>
                </div>
            </form>
        </div>
    </x-filament::section>
</x-filament-panels::page>
