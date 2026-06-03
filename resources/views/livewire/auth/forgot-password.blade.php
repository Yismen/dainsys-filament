<div
    class="w-full rounded-2xl p-8 md:p-10 shadow-sm backdrop-blur"
    style="background:var(--card-bg);border:1px solid var(--card-border);box-shadow:0 4px 24px rgba(0,0,0,.06)"
>
    <div class="mb-8 space-y-1.5 text-center">
        <h1 class="text-gradient-violet text-3xl font-black tracking-tight" style="font-family:'Syne',sans-serif">Reset your password</h1>
        <p style="color:var(--text-muted);font-family:'Plus Jakarta Sans',sans-serif" class="text-sm">We'll email you a secure reset link.</p>
    </div>

    @if ($statusMessage)
        <div class="mb-5 rounded-xl px-4 py-3 text-sm font-medium" style="background:rgba(16,185,129,.1);color:#059669;border:1px solid rgba(16,185,129,.2)">
            {{ $statusMessage }}
        </div>
    @endif

    <form class="space-y-5" wire:submit.prevent="sendResetLink">
        <div class="space-y-1.5">
            <label class="text-sm font-semibold" style="color:var(--text-muted)" for="email">Email</label>
            <input
                id="email"
                type="email"
                wire:model="email"
                autocomplete="email"
                class="w-full rounded-xl border px-4 py-3 text-sm shadow-sm outline-none transition focus:ring-2"
                style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-base);caret-color:#7c3aed;--tw-ring-color:rgba(124,58,237,.30)"
            />
            @error('email')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="btn-gradient w-full px-4 py-3.5 text-sm font-bold shadow-lg"
        >
            Email Reset Link
        </button>
    </form>

    <div class="mt-6 text-center">
        <a class="text-sm font-medium transition hover:opacity-70" style="color:#7c3aed" href="{{ route('login') }}">&larr; Back to sign in</a>
    </div>
</div>
