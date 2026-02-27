<div class="min-h-screen bg-white dark:bg-gray-900">
    <!-- Header -->
    <section class="bg-linear-to-r from-blue-50 to-orange-50 dark:from-gray-800 dark:to-gray-900 py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-4">
                @if ($article->categories->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach ($article->categories as $category)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
                <h1 class="text-5xl font-bold text-gray-900 dark:text-white">
                    {{ $article->title }}
                </h1>
                <div class="flex items-center space-x-4 text-gray-600 dark:text-gray-400 text-sm">
                    <span>By {{ $article->author->name }}</span>
                    <span>•</span>
                    <time datetime="{{ $article->created_at->toDateTimeString() }}">
                        {{ $article->created_at->format('F j, Y') }}
                    </time>
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="py-12">
        <article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($article->featured_image_path)
                <div class="mb-8 rounded-lg overflow-hidden">
                    <img src="{{ Storage::url($article->featured_image_path) }}" alt="{{ $article->title }}" class="w-full h-auto">
                </div>
            @endif

            @if ($article->excerpt)
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 italic">
                    {{ $article->excerpt }}
                </p>
            @endif

            <div class="prose prose-invert max-w-none dark:prose-invert">
                {!! $article->content !!}
            </div>

            <!-- SEO Meta -->
            @if ($article->meta_description || $article->meta_keywords)
                <hr class="my-8 border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    @if ($article->meta_description)
                        <p class="mb-2"><strong>Description:</strong> {{ $article->meta_description }}</p>
                    @endif
                    @if ($article->meta_keywords)
                        <p><strong>Keywords:</strong> {{ $article->meta_keywords }}</p>
                    @endif
                </div>
            @endif
        </article>

        <!-- Back to Blog -->
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center text-blue-600 dark:text-blue-500 hover:text-blue-700 dark:hover:text-blue-400">
                ← Back to Blog
            </a>
        </div>
    </section>
</div>
