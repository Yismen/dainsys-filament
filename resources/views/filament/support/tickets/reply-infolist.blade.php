@php
    $currentUserId = auth()->id();
@endphp

<div class="space-y-3">
    @forelse ($record->replies as $reply)
        @php
            $isCurrentUserReply = ! is_null($currentUserId) && (string) $reply->user_id === (string) $currentUserId;
        @endphp

        <article
            data-owner="{{ $isCurrentUserReply ? 'self' : 'other' }}"
            @class([
                'rounded-xl border px-4 py-3 shadow-sm transition-colors',
                'ml-8 border-blue-200 bg-blue-50 text-blue-950 dark:border-blue-800 dark:bg-blue-950/40 dark:text-blue-100' => $isCurrentUserReply,
                'mr-8 border-gray-200 bg-gray-50 text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100' => ! $isCurrentUserReply,
            ])
        >
            <div class="mb-1 flex items-center justify-between">
                <p
                    @class([
                        'text-sm font-semibold',
                        'italic' => $isCurrentUserReply,
                    ])
                >
                    {{ $reply->user?->name ?? __('Unknown user') }}
                </p>

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $reply->created_at?->diffForHumans() }}
                </p>
            </div>

            <p class="text-sm leading-relaxed">
                {{ $reply->content }}
            </p>
        </article>
    @empty
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No replies yet.') }}</p>
    @endforelse
</div>
