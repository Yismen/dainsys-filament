<div class="min-h-screen bg-white dark:bg-gray-900">
    <!-- Header -->
    <section class="bg-linear-to-r from-blue-50 to-orange-50 dark:from-gray-800 dark:to-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                Blog
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">
                Latest articles and insights
            </p>
        </div>
    </section>

    <!-- Content -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 lg:gap-x-8">
                <div class="lg:col-span-3">
                    <!-- Search Bar -->
                    <div
                        class="mb-8"
                        x-data="{ open: @entangle('searchOpen') }"
                        x-init="$watch('open', value => { if (value) $nextTick(() => $refs.searchInput.focus()); })"
                        x-on:click.away="open = false"
                    >
                        <div class="relative">
                            <button
                                x-show="!open"
                                type="button"
                                @click="open = true"
                                aria-label="Open search"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                            >
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>

                            <div x-show="open" x-transition class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>

                                <input
                                    x-ref="searchInput"
                                    type="text"
                                    wire:model.live="search"
                                    placeholder="Search articles"
                                    class="w-full pl-10 pr-4 py-2 border rounded-lg dark:bg-gray-800 dark:text-white transition-all duration-200"
                                />

                                @if(filled($search))
                                    <button
                                        type="button"
                                        wire:click="clearSearch"
                                        aria-label="Clear search"
                                        class="absolute right-10 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                    >
                                        &times;
                                    </button>
                                @endif

                                <div wire:loading class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <svg class="inline h-5 w-5 animate-spin text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Articles -->
                    @if ($this->articles->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach ($this->articles as $article)
                                <div wire:key="article-{{ $article->id }}">
                                    <x-article-card :article="$article" />
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-12">
                            {{ $this->articles->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-600 dark:text-gray-400 text-lg">
                                No articles available at the moment.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar: Categories (desktop only) -->
                <aside class="hidden lg:block lg:col-span-1">
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-6 sticky top-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Categories
                        </h2>

                        <ul class="space-y-2">
                            @foreach ($this->getCategories() as $category)
                                <li>
                                    <button
                                        wire:click="filterByCategory('{{ $category->slug }}')"
                                        class="w-full text-left px-3 py-2 rounded {{ $this->selectedCategory === $category->slug ? 'font-semibold bg-blue-600 dark:bg-blue-700 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}"
                                    >
                                        {{ $category->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        @if ($this->selectedCategory || filled($this->search))
                            <button
                                wire:click="clearFilters"
                                class="w-full mt-4 pt-4 border-t border-gray-300 dark:border-gray-700 px-3 py-2 text-left text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200"
                            >
                                Clear filters
                            </button>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>
