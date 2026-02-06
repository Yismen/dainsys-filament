<div class="w-full max-w-md space-y-6 rounded-3xl bg-white/90 p-8 shadow-2xl backdrop-blur">
    <div class="space-y-2 text-center">
        <h1 class="text-3xl font-bold text-slate-900">Set a new password</h1>
        <p class="text-slate-600">Choose a strong password to secure your account.</p>
    </div>

    <form class="space-y-5" wire:submit.prevent="resetPassword">
        <div class="space-y-2">
            <label class="text-sm font-medium text-slate-700" for="email">Email</label>
            <input
                id="email"
                type="email"
                wire:model="email"
                autocomplete="email"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
            />
            @error('email')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label class="text-sm font-medium text-slate-700" for="password">New password</label>
            <input
                id="password"
                type="password"
                wire:model="password"
                autocomplete="new-password"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
            />
            @error('password')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label class="text-sm font-medium text-slate-700" for="password_confirmation">Confirm password</label>
            <input
                id="password_confirmation"
                type="password"
                wire:model="password_confirmation"
                autocomplete="new-password"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
            />
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-linear-to-r from-blue-600 to-blue-700 px-4 py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-blue-500/30"
        >
            Reset Password
        </button>
    </form>
</div>
