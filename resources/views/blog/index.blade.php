@extends('layouts.landing-page')

@section('content')
<div class="min-h-screen bg-white dark:bg-gray-900">
        <!-- Header -->
        <section class="bg-linear-to-r from-amber-50 to-orange-50 dark:from-gray-800 dark:to-gray-900 py-12">
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
        <section class="py-2">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 lg:gap-x-8">
                    <div class="lg:col-span-3">
                        {{-- search bar --}}
                        <form method="GET" class="mb-6">
                            <div class="relative">
                                @if(request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Search articles"
                                    class="w-full pl-10 pr-4 py-2 border rounded-lg"
                                />
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a7 7 0 017 7 7 7 0 01-1.414 4.243l5.364 5.364-1.414 1.414-5.364-5.364A7 7 0 1111 4z" />
                                </svg>
                            </div>
                        </form>

                        @if ($articles->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                @foreach ($articles as $article)
                                    <x-article-card :article="$article" />
                                @endforeach
                            </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $articles->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-600 dark:text-gray-400 text-lg">
                            No articles available at the moment.
                        </p>
                    </div>
                @endif
                </div>

                    <!-- sidebar of categories (desktop only) placed to the right -->
                    <aside class="hidden lg:block lg:col-span-1">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Categories</h2>
                        <ul class="mt-4 space-y-2">
                            @foreach ($categories as $cat)
                                <li>
                                    <a href="{{ url('/blog?category=' . $cat->slug) }}"
                                       class="text-amber-600 dark:text-amber-400 hover:underline {{ request('category') === $cat->slug ? 'font-semibold' : '' }}">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                </div>
            </div>
        </section>
    </div>
@endsection
