<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('ui.book_library') }}</h1>
        </div>
    </x-slot>

    <div class="py-4">

        <!-- Main Content - Properly constrained width -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @auth
            @if(isset($recommendedBooks) && $recommendedBooks->count() > 0 && !request('search'))
                <!-- Hero Recommendation Slider -->
                <div class="mb-8 rounded-2xl bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                Recommended for you, {{ auth()->user()->name }}
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Handpicked suggestions based on your reading</p>
                        </div>
                    </div>
                    <div class="p-3 sm:p-6" x-data="homeRecommendationSlider()" x-init="init()">
                        <div class="relative">
                            <div class="flex transition-transform duration-500 ease-in-out"
                                 :style="`transform: translateX(-${currentSlide * slideWidth}px)`">
                                @foreach($recommendedBooks as $book)
                                    <div class="flex-shrink-0 mr-3 sm:mr-6 w-48 sm:w-56 md:w-64">
                                        <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 hover:border-purple-200 dark:hover:border-purple-600 hover:-translate-y-1 flex flex-col h-full min-h-[400px] sm:min-h-[480px] md:min-h-[520px]">
                                            <!-- Clickable Cover -->
                                            <a href="{{ route('books.show', $book->id) }}" class="relative aspect-[3/4] bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 overflow-hidden block">
                                                @if($book->cover_path)
                                                    <img src="{{ Storage::url($book->cover_path) }}" alt="{{ $book->title_en }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <div class="text-center">
                                                            <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900 dark:to-indigo-900 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-300">
                                                                <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                            </div>
                                                            <p class="text-xs font-medium text-gray-400 dark:text-gray-500">No Cover</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                <!-- Visual hover overlay -->
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                                                <!-- Badges -->
                                                <div class="absolute top-3 right-3">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 shadow-sm"><span class="w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse"></span>Available</span>
                                                </div>
                                                <div class="absolute top-3 left-3">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 shadow-sm">Recommended</span>
                                                </div>
                                            </a>
                                            <!-- Info -->
                                            <div class="p-3 sm:p-4 flex flex-col flex-grow">
                                                <h3 class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">
                                                    @if($book->language && $book->language->code === 'bn' && $book->title_bn)
                                                        {{ $book->title_bn }}
                                                    @else
                                                        {{ $book->title_en }}
                                                    @endif
                                                </h3>
                                                @if($book->primaryAuthor)
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">@if($book->language && $book->language->code === 'bn' && $book->primaryAuthor->name_bn){{ $book->primaryAuthor->name_bn }}@else{{ $book->primaryAuthor->name_en }}@endif</p>
                                                @endif
                                                @if($book->category)
                                                    <div class="mb-3"><span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">@if($book->language && $book->language->code === 'bn' && $book->category->name_bn){{ $book->category->name_bn }}@else{{ $book->category->name_en }}@endif</span></div>
                                                @endif
                                                @auth
                                                <div class="mt-auto pt-3 border-t border-gray-100 dark:border-gray-700">
                                                    @if(auth()->user()->hasRole(['Admin', 'Librarian']))
                                                        <!-- Admin Edit Button -->
                                                        <a href="{{ route('admin.books.edit', $book) }}" 
                                                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transform hover:scale-105 transition-all duration-200">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                            Edit Book
                                                        </a>
                                                    @else
                                                        @php
                                                            $existingLoan = \App\Models\Models\Loan::where('user_id', auth()->id())->where('book_id', $book->id)->whereIn('status',["pending","issued"]) ->first();
                                                        @endphp
                                                        @if($existingLoan)
                                                            <div class="text-center"><span class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">{{ ucfirst($existingLoan->status) }}</span></div>
                                                        @else
                                                            @if($book->available_copies > 0)
                                                                <form action="{{ route('books.request', $book->id) }}" method="POST" class="w-full">@csrf
                                                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transform hover:scale-105 transition-all duration-200">Quick Borrow</button>
                                                                </form>
                                                            @else
                                                                <form action="{{ route('books.reserve', $book->id) }}" method="POST" class="w-full">@csrf
                                                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transform hover:scale-105 transition-all duration-200">Reserve</button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Indicators -->
                        <div class="flex justify-center mt-4 sm:mt-6 space-x-2" x-show="totalSlides > 1">
                            <template x-for="i in totalSlides" :key="i">
                                <button @click="goToSlide(i-1)" class="w-2 h-2 rounded-full transition-all duration-200" :class="currentSlide === (i-1) ? 'bg-purple-600 w-6' : 'bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500'"></button>
                            </template>
                        </div>
                    </div>
                </div>
            @endif
            @endauth
            <!-- Enhanced Results Header with Search State -->
            <div class="flex flex-col items-center mb-6 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                @if(request()->filled('search'))
                    <!-- Search Results State -->
                    <div class="flex items-center justify-center mb-4">
                        <div class="flex items-center bg-white dark:bg-gray-800 px-4 py-2 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Search results for:</span>
                            <span class="ml-2 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-semibold">"{{ request('search') }}"</span>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
                        {{ $books->total() }} {{ $books->total() === 1 ? 'book' : 'books' }} found
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 text-center max-w-md">
                        @if($books->total() > 0)
                            Browse through the search results below or use the filters to refine your search further.
                        @else
                            No books match your search criteria. Try different keywords or browse all available books.
                        @endif
                    </p>
                @else
                    <!-- Default Library State -->
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
                        {{ __('ui.books_found', ['count' => $books->total()]) }}
                    </h2>
                    @if(request()->hasAny(['category', 'language', 'year']) || (request('availability') && request('availability') != 'available'))
                        <p class="text-gray-600 dark:text-gray-400 text-center max-w-md">
                            {{ __('ui.filtered_results') }} - Use the search bar above or filters to find specific books.
                        </p>
                    @else
                        <p class="text-gray-600 dark:text-gray-400 text-center max-w-md">
                            Explore our collection of books. Use the search bar above to find specific titles, authors, or ISBNs.
                        </p>
                    @endif
                @endif
                
                <!-- Quick Actions -->
                <div class="flex flex-wrap gap-2 mt-4">
                    @if(request()->filled('search'))
                        <a href="{{ route('home') }}" class="inline-flex items-center px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            View All Books
                        </a>
                    @endif
                    @if(request()->hasAny(['category', 'language', 'year', 'availability', 'sort']) && (request('sort') != 'latest' || (request('availability') && request('availability') != 'available')))
                        <a href="{{ route('home', request()->only('search')) }}" class="inline-flex items-center px-3 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear Filters
                        </a>
                    @endif
                </div>
            </div>
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar {{ __('filters.filters') }} -->
                <div class="lg:w-1/4">
                    <!-- Mobile {{ __('filters.filters') }} Dropdown -->
                    <div x-data="{ open: false }" class="lg:hidden mb-4">
                        <button @click="open = !open" class="w-full inline-flex items-center justify-between px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                            <span class="flex items-center gap-2 text-gray-800 dark:text-gray-200 font-semibold">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                {{ __('filters.filters') }}
                            </span>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-transition class="mt-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <form method="GET" action="{{ route('home') }}" class="p-4 space-y-6">
                                <!-- Preserve search parameter -->
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                <div>
                                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('filters.category') }}</label>
                                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">{{ __('filters.all_categories') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('filters.language') }}</label>
                                    <select name="language" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">All {{ __('filters.language') }}s</option>
                                        @foreach($languages as $language)
                                            <option value="{{ $language->code }}" {{ request('language') == $language->code ? 'selected' : '' }}>{{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('filters.publication_year') }}</label>
                                    <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">{{ __('filters.all_years') }}</option>
                                        @foreach($years as $year)
                                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('filters.availability') }}</label>
                                    <select name="availability" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="all" {{ !request('availability') || request('availability') == 'all' ? 'selected' : '' }}>{{ __('filters.all_books') }}</option>
                                        <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>{{ __('filters.available_only_default') }}</option>
                                        <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>{{ __('filters.unavailable_only') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('filters.sort_by') }}</label>
                                    <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('filters.latest_first') }}</option>
                                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>{{ __('filters.title_a_z') }}</option>
                                        <option value="author" {{ request('sort') == 'author' ? 'selected' : '' }}>{{ __('filters.author_a_z') }}</option>
                                        <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>{{ __('filters.year_newest') }}</option>
                                    </select>
                                </div>
                                <div class="flex gap-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">Apply</button>
                                    <a href="{{ route('home') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-center transition">Clear</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Current {{ __('filters.filters') }} Display -->
                    @if(request()->hasAny(['search', 'category', 'language', 'year', 'availability', 'sort']) && (request('sort') != 'latest' || (request('availability') && request('availability') != 'available')))
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-white flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                    </svg>
                                    {{ __('filters.active') }} {{ __('filters.filters') }}
                                </h3>
                                <a href="{{ route('home') }}" class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    {{ __('filters.clear_all') }}
                                </a>
                            </div>
                            <div class="space-y-2">
                                @if($currentFilters['search'])
                                    <div class="flex items-center justify-between bg-blue-50 dark:bg-blue-900/20 rounded-md px-3 py-2">
                                        <span class="text-sm text-blue-800 dark:text-blue-200">{{ __('filters.search') }}: "{{ $currentFilters['search'] }}"</span>
                                        <a href="{{ route('home', request()->except('search')) }}" class="text-blue-600 hover:text-blue-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                @if($currentFilters['category'])
                                    <div class="flex items-center justify-between bg-green-50 dark:bg-green-900/20 rounded-md px-3 py-2">
                                        <span class="text-sm text-green-800 dark:text-green-200">{{ __('filters.category') }}: {{ $currentFilters['category']->name_en }}</span>
                                        <a href="{{ route('home', request()->except('category')) }}" class="text-green-600 hover:text-green-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                @if($currentFilters['language'])
                                    <div class="flex items-center justify-between bg-purple-50 dark:bg-purple-900/20 rounded-md px-3 py-2">
                                        <span class="text-sm text-purple-800 dark:text-purple-200">{{ __('filters.language') }}: {{ $languages->where('code', $currentFilters['language'])->first()->name ?? ucfirst($currentFilters['language']) }}</span>
                                        <a href="{{ route('home', request()->except('language')) }}" class="text-purple-600 hover:text-purple-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                @if($currentFilters['year'])
                                    <div class="flex items-center justify-between bg-orange-50 dark:bg-orange-900/20 rounded-md px-3 py-2">
                                        <span class="text-sm text-orange-800 dark:text-orange-200">{{ __('filters.publication_year') }}: {{ $currentFilters['year'] }}</span>
                                        <a href="{{ route('home', request()->except('year')) }}" class="text-orange-600 hover:text-orange-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                @if($currentFilters['availability'] && $currentFilters['availability'] != 'available')
                                    <div class="flex items-center justify-between bg-indigo-50 dark:bg-indigo-900/20 rounded-md px-3 py-2">
                                        <span class="text-sm text-indigo-800 dark:text-indigo-200">{{ __('filters.availability') }}: {{ $currentFilters['availability'] == 'all' ? __('filters.all_books') : ucfirst($currentFilters['availability']) }}</span>
                                        <a href="{{ route('home', request()->except('availability')) }}" class="text-indigo-600 hover:text-indigo-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                @if($currentFilters['sort'] && $currentFilters['sort'] != 'latest')
                                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 rounded-md px-3 py-2">
                                        <span class="text-sm text-gray-800 dark:text-gray-200">{{ __('filters.sort_by') }}: {{ ucfirst(str_replace('_', ' ', $currentFilters['sort'])) }}</span>
                                        <a href="{{ route('home', request()->except('sort')) }}" class="text-gray-600 hover:text-gray-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Filter Navigation -->
                    <div class="hidden lg:block bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                {{ __('filters.filters') }}
                            </h3>
                        </div>
                        
                        <form method="GET" action="{{ route('home') }}" class="p-4 space-y-6">
                            <!-- Preserve search parameter -->
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            
                            <!-- {{ __('filters.category') }} Filter -->
                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    {{ __('filters.category') }}
                                </label>
                                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition duration-200">
                                    <option value="">{{ __('filters.all_categories') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- {{ __('filters.language') }} Filter -->
                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                                    </svg>
                                    {{ __('filters.language') }}
                                </label>
                                <select name="language" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition duration-200">
                                    <option value="">All {{ __('filters.language') }}s</option>
                                    @foreach($languages as $language)
                                        <option value="{{ $language->code }}" {{ request('language') == $language->code ? 'selected' : '' }}>
                                            {{ $language->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Year Filter -->
                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ __('filters.publication_year') }}
                                </label>
                                <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition duration-200">
                                    <option value="">{{ __('filters.all_years') }}</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- {{ __('filters.availability') }} Filter -->
                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ __('filters.availability') }}
                                </label>
                                <select name="availability" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition duration-200">
                                    <option value="all" {{ !request('availability') || request('availability') == 'all' ? 'selected' : '' }}>
                                        {{ __('filters.all_books') }}
                                    </option>
                                    <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>
                                        {{ __('filters.available_only_default') }}
                                    </option>
                                    <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>
                                        {{ __('filters.unavailable_only') }}
                                    </option>
                                </select>
                            </div>

                            <!-- Sort Options -->
                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                    {{ __('filters.sort_by') }}
                                </label>
                                <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition duration-200">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('filters.latest_first') }}</option>
                                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>{{ __('filters.title_a_z') }}</option>
                                    <option value="author" {{ request('sort') == 'author' ? 'selected' : '' }}>{{ __('filters.author_a_z') }}</option>
                                    <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>{{ __('filters.year_newest') }}</option>
                                </select>
                            </div>

                            <div class="flex space-x-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                    </svg>
                                    Apply
                                </button>
                                <a href="{{ route('home') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 text-center flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Clear
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:w-3/4">

                    <!-- Enhanced Books Grid -->
                    @if($books->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            @foreach($books as $book)
                                <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 hover:border-blue-200 dark:hover:border-blue-600 hover:-translate-y-1">
                                    <!-- Enhanced Book Cover -->
                                    <div class="relative aspect-[4/3] bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 overflow-hidden">
                                        @if($book->cover_path)
                                            <img src="{{ Storage::url($book->cover_path) }}" 
                                                 alt="{{ $book->title_en }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                                <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900 dark:to-indigo-900 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-300">
                                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                                </div>
                                                <p class="text-xs font-medium">No Cover</p>
                                            </div>
                                        @endif
                                        
                                        <!-- {{ __('filters.availability') }} Badge -->
                                        <div class="absolute top-2 right-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $book->available_copies > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                {{ $book->available_copies > 0 ? 'Available' : 'Unavailable' }}
                                            </span>
                                        </div>
                                        
                                        <!-- Hover Overlay -->
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center">
                                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                @if(auth()->check() && auth()->user()->hasRole(['Admin', 'Librarian']))
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('admin.books.edit', $book) }}" 
                                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg shadow-lg text-sm font-medium transition-colors duration-200">
                                                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                            Edit
                                                        </a>
                                                        <a href="{{ route('books.show', $book) }}" 
                                                           class="bg-white dark:bg-gray-800 text-gray-800 dark:text-white px-3 py-2 rounded-lg shadow-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                            </svg>
                                                            View
                                                        </a>
                                                    </div>
                                                @else
                                                    <a href="{{ route('books.show', $book) }}" 
                                                       class="bg-white dark:bg-gray-800 text-gray-800 dark:text-white px-3 py-2 rounded-lg shadow-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                        View Details
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Enhanced Book Info -->
                                    <div class="p-3 sm:p-4">
                                        <!-- Title -->
                                        <h3 class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white mb-1 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                            @if($book->language && $book->language->code === 'bn' && $book->title_bn)
                                                {{ $book->title_bn }}
                                            @else
                                            {{ $book->title_en }}
                                            @endif
                                        </h3>
                                        
                                        <!-- {{ __('filters.author') }} -->
                                        @if($book->primaryAuthor)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1 flex items-center">
                                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                @if($book->language && $book->language->code === 'bn' && $book->primaryAuthor->name_bn)
                                                    {{ $book->primaryAuthor->name_bn }}
                                                @else
                                                    {{ $book->primaryAuthor->name_en }}
                                                @endif
                                            </p>
                                        @endif
                                        
                                        <!-- {{ __('filters.category') }} -->
                                        @if($book->category)
                                            <div class="mb-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                    </svg>
                                                    @if($book->language && $book->language->code === 'bn' && $book->category->name_bn)
                                                        {{ $book->category->name_bn }}
                                                    @else
                                                {{ $book->category->name_en }}
                                                    @endif
                                            </span>
                                            </div>
                                        @endif
                                        
                                        <!-- Book Details - Fixed Layout -->
                                        <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                                            <div class="flex flex-col space-y-1">
                                                <!-- {{ __('filters.publication_year') }} and Pages -->
                                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                @if($book->publication_year)
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <span>{{ $book->publication_year }}</span>
                                                @endif
                                                @if($book->pages)
                                                        <span class="mx-2">•</span>
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                        </svg>
                                                        <span>{{ $book->pages }} pages</span>
                                                @endif
                                            </div>
                                                <!-- Available Copies -->
                                                <div class="flex items-center text-xs font-semibold {{ $book->available_copies > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>{{ $book->available_copies }} {{ $book->available_copies === 1 ? 'copy' : 'copies' }} available</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            @auth
                                                @if(auth()->user()->hasRole(['Admin', 'Librarian']))
                                                    <!-- Admin Edit Button -->
                                                    <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                                        <a href="{{ route('admin.books.edit', $book) }}" 
                                                           class="w-full inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-medium rounded-lg shadow-sm hover:shadow-md transform hover:scale-105 transition-all duration-200">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                            <span class="hidden sm:inline">Edit Book</span>
                                                            <span class="sm:hidden">Edit</span>
                                                        </a>
                                                    </div>
                                                @elseif(Auth::user()->status === 'approved')
                                                    @php
                                                        $existingLoan = \App\Models\Models\Loan::where('user_id', auth()->id())
                                                            ->where('book_id', $book->id)
                                                            ->whereIn('status',["pending","issued"])
                                                            ->first();
                                                    @endphp
                                                    <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                                        @if($existingLoan)
                                                            <div class="text-center">
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                    {{ ucfirst($existingLoan->status) }}
                                                                </span>
                                                            </div>
                                                        @else
                                                            @if($book->available_copies > 0)
                                                                <form action="{{ route('books.request', $book) }}" method="POST" class="w-full">
                                                                    @csrf
                                                                    <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white text-xs sm:text-sm font-medium rounded-lg shadow-sm hover:shadow-md transform hover:scale-105 transition-all duration-200">
                                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                        </svg>
                                                                        <span class="hidden sm:inline">Borrow</span>
                                                                        <span class="sm:hidden">Get</span>
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <form action="{{ route('books.reserve', $book) }}" method="POST" class="w-full">
                                                                    @csrf
                                                                    <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-medium rounded-lg shadow-sm hover:shadow-md transform hover:scale-105 transition-all duration-200">
                                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                        </svg>
                                                                        <span class="hidden sm:inline">Reserve</span>
                                                                        <span class="sm:hidden">Wait</span>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                                        <div class="text-center">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                                </svg>
                                                                {{ __('filters.pending') }} Approval
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                                    <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-lg transition-colors duration-200">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                                        </svg>
                                                        Sign In to Borrow
                                                    </a>
                                                </div>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $books->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">No books found</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">We couldn't find any books matching your criteria. Try adjusting your search or filters.</p>
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Clear {{ __('filters.filters') }}
                                </a>
                                <button onclick="document.querySelector('nav input[type=\"text\"]').focus()" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Try Different {{ __('filters.search') }}
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function homeRecommendationSlider() {
            return {
                currentSlide: 0,
                slideWidth: 192, // Base width for mobile (w-48)
                totalSlides: 0,
                visibleSlides: 4,
                init() {
                    this.updateSlideWidth();
                    this.updateVisibleSlides();
                    this.totalSlides = Math.ceil({{ isset($recommendedBooks) ? $recommendedBooks->count() : 0 }} / this.visibleSlides);
                    setInterval(() => { if (this.totalSlides > 1) this.nextSlide(); }, 5000);
                },
                updateSlideWidth() {
                    if (window.innerWidth < 640) {
                        this.slideWidth = 192; // w-48
                    } else if (window.innerWidth < 768) {
                        this.slideWidth = 224; // w-56
                    } else {
                        this.slideWidth = 256; // w-64
                    }
                },
                updateVisibleSlides() {
                    if (window.innerWidth < 640) {
                        this.visibleSlides = 2; // Mobile: 2 books
                    } else if (window.innerWidth < 768) {
                        this.visibleSlides = 2; // Small tablet: 2 books
                    } else if (window.innerWidth < 1024) {
                        this.visibleSlides = 3; // Tablet: 3 books
                    } else {
                        this.visibleSlides = 4; // Desktop: 4 books (original)
                    }
                    this.updateSlideWidth();
                    this.totalSlides = Math.ceil({{ isset($recommendedBooks) ? $recommendedBooks->count() : 0 }} / this.visibleSlides);
                    if (this.currentSlide >= this.totalSlides) this.currentSlide = Math.max(0, this.totalSlides - 1);
                },
                nextSlide() { this.currentSlide = (this.currentSlide + 1) % Math.max(this.totalSlides, 1); },
                goToSlide(i) { this.currentSlide = i; }
            }
        }
        window.addEventListener('resize', () => {
            const slider = Alpine.$data(document.querySelector('[x-data="homeRecommendationSlider()"]'));
            if (slider) slider.updateVisibleSlides();
        });
    </script>
</x-app-layout>
