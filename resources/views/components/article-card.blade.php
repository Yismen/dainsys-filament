<div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden">
    <div class="h-48 bg-linear-to-br from-amber-200 to-orange-300 dark:from-amber-900 dark:to-orange-900 flex items-center justify-center">
        @if ($article->featured_image_path)
            <img src="{{ Storage::url($article->featured_image_path) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
        @else
            <svg class="w-12 h-12 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        @endif
    </div>

    <div class="p-6">
        <div class="flex flex-wrap gap-2 mb-3">
            @foreach ($article->categories as $category)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-100">
                    {{ $category->name }}
                </span>
            @endforeach
        </div>

        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
            {{ $article->title }}
        </h3>

        @if ($article->excerpt)
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                {{ $article->excerpt }}
            </p>
        @endif

        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
            <span>{{ $article->author->name }}</span>
            <time datetime="{{ $article->created_at->toDateTimeString() }}">
                {{ $article->created_at->format('M d, Y') }}
            </time>
        </div>

        <a href="{{ route('blog.show', $article) }}" class="inline-flex items-center text-amber-600 dark:text-amber-500 hover:text-amber-700 dark:hover:text-amber-400 font-medium">
            Read Article →
        </a>
    </div>
</div>
