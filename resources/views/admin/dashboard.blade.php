<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">{{ __('filters.admin') }} {{ __('ui.admin_dashboard') }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm rounded border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700">{{ __('ui.users') }}</a>
                <a href="{{ route('admin.loans.index') }}" class="px-3 py-2 text-sm rounded border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700">{{ __('navigation.loans') }}</a>
                <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 text-sm rounded border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700">{{ __('ui.reports') }}</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stat cards: gradient with icons -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="p-4 rounded-xl shadow-sm text-white bg-gradient-to-r from-blue-500 to-indigo-600">
                    <div class="flex items-center">
                        <div class="p-2 bg-white/10 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs opacity-80">{{ __('ui.total_books') }}</div>
                            <div class="text-2xl font-bold">{{ $stats['books'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="p-4 rounded-xl shadow-sm text-white bg-gradient-to-r from-emerald-500 to-green-600">
                    <div class="flex items-center">
                        <div class="p-2 bg-white/10 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs opacity-80">{{ __('ui.available') }}</div>
                            <div class="text-2xl font-bold">{{ $stats['available_books'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.loans.index', ['q' => '', 'status' => 'pending']) }}" class="block p-4 rounded-xl shadow-sm text-white bg-gradient-to-r from-purple-500 to-violet-600 hover:from-purple-600 hover:to-violet-700 transition-all duration-200 transform hover:scale-105 cursor-pointer">
                    <div class="flex items-center">
                        <div class="p-2 bg-white/10 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs opacity-80">{{ __('ui.pending_loans') }}</div>
                            <div class="text-2xl font-bold">{{ $stats['pending_loans'] ?? 0 }}</div>
                        </div>
                    </div>
                </a>
                <div class="p-4 rounded-xl shadow-sm text-white bg-gradient-to-r from-sky-500 to-blue-600">
                    <div class="flex items-center">
                        <div class="p-2 bg-white/10 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs opacity-80">{{ __('ui.users') }}</div>
                            <div class="text-2xl font-bold">{{ $stats['users'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="p-4 rounded-xl shadow-sm text-white bg-gradient-to-r from-amber-500 to-orange-600">
                    <div class="flex items-center">
                        <div class="p-2 bg-white/10 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"/></svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs opacity-80">{{ __('ui.active_loans') }}</div>
                            <div class="text-2xl font-bold">{{ $stats['loans_active'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Modern Filter Sidebar -->
                <div class="lg:w-1/4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4.5h18M5.25 8.25h13.5m-12 3.75h10.5m-9 3.75h7.5M9 19.5h6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ __('ui.filters_and_search') }}</h3>
                                    <p class="text-indigo-100 text-sm">{{ __('ui.find_specific_books') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <form method="GET" class="p-6 space-y-6">
                            <!-- {{ __('filters.search') }} Section -->
                            <div class="space-y-3">
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    {{ __('ui.search_books') }}
                                </label>
                                <div class="relative">
                                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="{{ __('ui.title_author_isbn') }}" class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200" />
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- {{ __('filters.filter_options') }} -->
                            <div class="space-y-4">
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                    </svg>
                                    {{ __('ui.filter_options') }}
                                </label>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('filters.category') }}</label>
                                        <select name="category" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200">
                                            <option value="">{{ __('filters.all_categories') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ ($filters['category'] ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('filters.language') }}</label>
                                        <select name="language" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200">
                                            <option value="">All {{ __('filters.language') }}s</option>
                                            @foreach($languages as $code)
                                                <option value="{{ $code }}" {{ ($filters['language'] ?? '') == $code ? 'selected' : '' }}>{{ strtoupper($code) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('filters.publication_year') }}</label>
                                        <select name="year" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200">
                                            <option value="">{{ __('filters.all_years') }}</option>
                                            @foreach($years as $year)
                                                <option value="{{ $year }}" {{ ($filters['year'] ?? '') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col gap-3 pt-4">
                                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                    <div class="flex items-center justify-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        <span>{{ __('ui.apply_filters') }}</span>
                                    </div>
                                </button>
                                <a href="{{ route('admin.home') }}" class="w-full px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 text-center">
                                    {{ __('filters.clear_all') }}
                                </a>
                            </div>
                        </form>

                        <!-- {{ __('filters.quick_links') }} Section -->
                        <div class="px-6 pb-6">
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    {{ __('ui.quick_links') }}
                                </div>
                                <div class="space-y-2">
                                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-xl bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 hover:from-gray-100 hover:to-gray-200 dark:hover:from-gray-600 dark:hover:to-gray-500 transition-all duration-200 group">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('ui.manage_users') }}</span>
                                    </a>
                                    <a href="{{ route('admin.loans.index') }}" class="flex items-center px-4 py-3 rounded-xl bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 hover:from-gray-100 hover:to-gray-200 dark:hover:from-gray-600 dark:hover:to-gray-500 transition-all duration-200 group">
                                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-violet-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('ui.manage_loans') }}</span>
                                    </a>
                                    <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-3 rounded-xl bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 hover:from-gray-100 hover:to-gray-200 dark:hover:from-gray-600 dark:hover:to-gray-500 transition-all duration-200 group">
                                        <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-green-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('ui.reports') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Grid + Activity -->
                <div class="lg:w-3/4 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                        <div class="p-4 border-b dark:border-gray-700 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('ui.books') }}</h3>
                            <a href="{{ route('admin.books.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">{{ __('ui.add_book') }}</a>
                        </div>
                        <div class="p-4">
                            @if(isset($books) && $books->count())
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($books as $book)
                                        <div class="group cursor-pointer bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700">
                                            <div class="relative aspect-[4/5] bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 overflow-hidden">
                                                @if($book->cover_path)
                                                    <img src="{{ Storage::url($book->cover_path) }}" alt="{{ $book->title_en }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <div class="text-center">
                                                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                            <p class="text-xs font-medium text-gray-400 dark:text-gray-500">{{ __('ui.no_cover') }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="absolute top-2 right-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $book->available_copies > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                        {{ $book->available_copies > 0 ? __('ui.available') : __('ui.unavailable') }}
                                                    </span>
                                                </div>
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center">
                                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                        <a href="{{ route('admin.books.edit', $book) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">{{ __('ui.edit') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-4">
                                                <h3 class="font-semibold text-base text-gray-900 dark:text-white mb-1 line-clamp-2">
                                                    @if($book->language && $book->language->code === 'bn' && $book->title_bn)
                                                        {{ $book->title_bn }}
                                                    @else
                                                        {{ $book->title_en }}
                                                    @endif
                                                </h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                                    @if($book->primaryAuthor)
                                                        @if($book->language && $book->language->code === 'bn' && $book->primaryAuthor->name_bn)
                                                            {{ $book->primaryAuthor->name_bn }}
                                                        @else
                                                            {{ $book->primaryAuthor->name_en }}
                                                        @endif
                                                    @else
                                                        {{ $book->primaryAuthor ? ($book->primaryAuthor->name_en ?? $book->primaryAuthor->name_bn) : __('ui.unknown_author') }}
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $book->publication_year }} • {{ $book->available_copies }}/{{ $book->total_copies }} {{ __('ui.copies') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">{{ $books->links() }}</div>
                            @else
                                <div class="text-gray-600 dark:text-gray-400">{{ __('ui.no_books_found') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                        <div class="p-4 border-b dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('ui.recent_activity') }}</h3>
                        </div>
                        <div class="p-4 divide-y dark:divide-gray-700">
                            @forelse(($activities ?? []) as $activity)
                                <div class="py-3 text-sm text-gray-700 dark:text-gray-300 flex items-center justify-between">
                                    <div>
                                        <span class="font-medium">{{ $activity->user_name ?? 'System' }}</span>
                                        <span class="text-gray-500">{{ $activity->action ?? $activity->description ?? '' }}</span>
                                    </div>
                                    <span class="text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <div class="py-6 text-gray-600 dark:text-gray-400">{{ __('ui.no_recent_activity') }}</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
