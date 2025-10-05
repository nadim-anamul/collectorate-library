<nav x-data="{ open: false, booksOpen: false, contributorsOpen: false, membersOpen: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Navigation Row -->
        <div class="flex justify-between {{ auth()->check() && auth()->user()->hasRole(['Admin', 'Librarian']) ? 'h-16 py-2' : 'h-16' }}">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-sm sm:text-xl text-gray-800 dark:text-white">{{ __('ui.app_name_line1') }}</span>
                </a>
            </div>

            <!-- Search Bar (Desktop) - Regular Users Only -->
            @unless(auth()->check() && auth()->user()->hasRole(['Admin', 'Librarian']))
            <div class="hidden md:flex items-center flex-1 max-w-md mx-4">
                <div class="w-full" x-data="headerSearch()" @click.outside="clearSearch()">
                    <div class="relative group">
                        <input type="text"
                               x-model="query"
                               @input="search()"
                               @keydown.arrow-down="navigateDown()"
                               @keydown.arrow-up="navigateUp()"
                               @keydown.enter="selectResult()"
                               @keydown.escape="clearSearch()"
                               placeholder="{{ __('filters.search') }} books, authors, {{ __('filters.isbn') }}..."
                               class="w-full px-4 py-2 pl-10 pr-10 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:border-blue-400 dark:focus:ring-blue-400/20 transition-all duration-200">

                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-4 h-4 text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <div x-show="query.length > 0" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <button @click="clearSearch()" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div x-show="query.length === 0" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <kbd class="px-1.5 py-0.5 text-xs font-medium text-gray-400 bg-gray-200 dark:bg-gray-600 dark:text-gray-500 rounded border border-gray-300 dark:border-gray-500">⌘K</kbd>
                        </div>

                        <div x-show="isSearching" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-4 h-4 text-blue-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>

                        <!-- Search Results Dropdown -->
                        <div x-show="results.length > 0"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 transform scale-95 translate-y-2"
                             class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-80 overflow-y-auto backdrop-blur-sm">
                            <template x-for="(book, index) in results" :key="book.id">
                                <div @click="selectBook(book)"
                                     :class="{ 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800': selectedIndex === index, 'hover:bg-gray-50 dark:hover:bg-gray-700': selectedIndex !== index }"
                                     class="flex items-start p-3 border-b border-gray-100 dark:border-gray-700 cursor-pointer transition-all duration-200 last:border-b-0 hover:shadow-sm group">
                                    <div class="flex-shrink-0 w-10 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900 dark:to-indigo-900 rounded flex items-center justify-center mr-3 shadow-sm group-hover:shadow-md transition-shadow duration-200">
                                        <template x-if="book.cover_image">
                                            <img :src="book.cover_image" :alt="book.title" class="w-full h-full object-cover rounded">
                                        </template>
                                        <template x-if="!book.cover_image">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </template>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate" x-text="book.title"></h3>
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200" x-text="book.status"></span>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-300 truncate mt-1" x-text="book.primary_author?.name || 'Unknown {{ __('filters.author') }}'"></p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <template x-if="book.category">
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200" x-text="book.category.name"></span>
                                            </template>
                                            <template x-if="book.publication_year">
                                                <span class="text-xs text-gray-500 dark:text-gray-400" x-text="book.publication_year"></span>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 ml-2">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- No Results -->
                        <div x-show="query && results.length === 0 && !isSearching"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 transform scale-95 translate-y-2"
                             class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 text-center backdrop-blur-sm">
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">No books found</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Try different keywords or press Enter to search all books</p>
                        </div>
                    </div>
                </div>
            </div>
            @endunless

            <!-- Main Navigation -->
            <div class="hidden md:flex items-center space-x-1">
                <!-- Browse Books -->
                <a href="{{ route('home') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    {{ __('navigation.browse_books') }}
                </a>
                    
                    @auth
                    <!-- My Dashboard (hidden for Admin/Librarian to avoid duplication) -->
                    @unlessrole('Admin|Librarian')
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        {{ __('navigation.my_dashboard') }}
                    </a>
                    @endunlessrole
                        
                        @role('Admin|Librarian')
                        <!-- Books Dropdown -->
                        <div class="relative" x-data="{ open: false }" x-cloak @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 transition-all duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span class="hidden lg:inline">{{ __('navigation.books') }}</span>
                                <svg class="w-3 h-3 ml-1 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                    </button>
                            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">
                                <a href="{{ route('admin.books.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    {{ __('navigation.manage_books') }}
                                </a>
                                <a href="{{ route('admin.books.create') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ __('navigation.add_new_book') }}
                                </a>
                                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    {{ __('navigation.categories') }}
                                </a>
                                <a href="{{ route('admin.tags.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                    {{ __('navigation.tags') }}
                                </a>
                            </div>
                                        </div>

                        <!-- {{ __('navigation.contributors') }} Dropdown -->
                        <div class="relative" x-data="{ open: false }" x-cloak @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 transition-all duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="hidden lg:inline">{{ __('navigation.contributors') }}</span>
                                <svg class="w-3 h-3 ml-1 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                    </button>
                            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">
                                <a href="{{ route('admin.authors.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ __('navigation.authors') }}
                                </a>
                                <a href="{{ route('admin.authors.create') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ __('navigation.add_author') }}
                                </a>
                                <a href="{{ route('admin.publishers.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    {{ __('navigation.publishers') }}
                                </a>
                                <a href="{{ route('admin.publishers.create') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ __('navigation.add_publisher') }}
                                </a>
                                <a href="{{ route('admin.languages.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                                            </svg>
                                    {{ __('navigation.languages') }}
                                </a>
                            </div>
                                        </div>

                        <a href="{{ route('admin.loans.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 transition-all duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>
                            {{ __('navigation.loans') }}
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 transition-all duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ __('navigation.users') }}
                        </a>
                        <a href="{{ route('admin.home') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 transition-all duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/></svg>
                            {{ __('navigation.admin') }}
                        </a>

                        <!-- Removed {{ __('navigation.admin') }} dropdown: replaced with direct {{ __('navigation.admin') }} link above -->
                        @endrole
                    @endauth
            </div>

            <!-- Right Side - User Menu, Theme Toggle & Mobile Menu Button -->
            <div class="flex items-center space-x-4">
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 dark:hover:bg-gray-700" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <!-- Hamburger icon -->
                        <svg x-show="!open" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Close icon -->
                        <svg x-show="open" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- Language Toggle (Desktop Only) -->
                <div class="relative hidden md:block" x-data="{ open: false }" x-cloak @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                        </svg>
                        <span class="ml-1 text-xs font-medium">{{ app()->getLocale() === 'bn' ? 'বাংলা' : 'EN' }}</span>
                    </button>
                    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">
                        <a href="{{ route('language.switch', 'en') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <span class="mr-3 text-lg">🇺🇸</span>
                            <div>
                                <div class="font-medium">English</div>
                                <div class="text-xs text-gray-500">{{ __('navigation.switch_to_english') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('language.switch', 'bn') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <span class="mr-3 text-lg">🇧🇩</span>
                            <div>
                                <div class="font-medium">বাংলা</div>
                                <div class="text-xs text-gray-500">{{ __('navigation.switch_to_bangla') }}</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Theme Toggle (Desktop Only) -->
                <button x-data @click="$dispatch('theme-toggled')" class="hidden md:block p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>

                @auth
                <!-- Notifications -->
                <script>
                    window.userIsAdmin = @json(auth()->check() && auth()->user()->hasRole(['Admin', 'Librarian']));
                    console.log('User is admin:', window.userIsAdmin);
                </script>
                <div x-data="notifications()" x-init="init()" class="relative" @click.outside="open=false">
                    <button @click="toggle()" class="relative p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span x-show="unread>0" x-text="unread" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-red-600 rounded-full"></span>
                    </button>
                    <div x-show="open" x-cloak x-transition class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">
                        <div class="px-4 py-2 flex items-center justify-between">
                            <a href="{{ route('notifications.all') }}" class="text-sm font-semibold text-blue-600 hover:underline">{{ __('navigation.notifications') }}</a>
                            <button @click="markAllRead()" class="text-xs text-blue-600 hover:underline">{{ __('navigation.mark_all_read') }}</button>
                        </div>
                        <template x-if="items.length===0">
                            <div class="px-4 py-6 text-sm text-gray-500 dark:text-gray-400">{{ __('navigation.no_notifications') }}</div>
                        </template>
                        <div class="max-h-80 overflow-auto">
                            <template x-for="n in items" :key="n.id">
                                <div class="block">
                                    <template x-if="is{{ __('navigation.admin') }}">
                                        <a :href="n.data.url || '#'" @click.prevent="markOneRead(n)" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <div class="flex items-start">
                                                <div class="flex-1">
                                                    <p class="text-sm" :class="n.read_at ? 'text-gray-500 dark:text-gray-400' : 'text-gray-800 dark:text-gray-100 font-medium'" x-text="n.data.message"></p>
                                                    <p class="text-xs text-gray-400 mt-1" x-text="n.created_at"></p>
                                                </div>
                                                <span x-show="!n.read_at" class="ml-2 mt-1 w-2 h-2 bg-blue-500 rounded-full"></span>
                                            </div>
                                        </a>
                                    </template>
                                    <template x-if="!is{{ __('navigation.admin') }}">
                                        <div @click="markOneRead(n)" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                            <div class="flex items-start">
                                                <div class="flex-1">
                                                    <p class="text-sm" :class="n.read_at ? 'text-gray-500 dark:text-gray-400' : 'text-gray-800 dark:text-gray-100 font-medium'" x-text="n.data.message"></p>
                                                    <p class="text-xs text-gray-400 mt-1" x-text="n.created_at"></p>
                                                </div>
                                                <span x-show="!n.read_at" class="ml-2 mt-1 w-2 h-2 bg-blue-500 rounded-full"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                @endauth

                @guest
                    <!-- Guest Menu -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition-colors duration-200">
                        {{ __('navigation.login') }}
                    </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        {{ __('navigation.register') }}
                    </a>
                    </div>
                @else
                    <!-- User Dropdown (Desktop Only) -->
                    <div class="relative hidden md:block" x-data="{ open: false }" x-cloak @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center space-x-2 px-2 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <div class="w-7 h-7 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="hidden lg:block text-sm font-medium text-gray-900 dark:text-white truncate max-w-24">
                                {{ Auth::user()->name }}
                            </span>
                            <svg class="w-3 h-3 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">
                            <!-- User Info -->
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                                <span class="inline-flex items-center px-2 py-1 mt-2 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ ucfirst(Auth::user()->status) }}
                                </span>
                            </div>
                            
                            <!-- Menu Items -->
                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                </svg>
                                {{ __('navigation.dashboard') }}
                            </a>

                            <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('navigation.my_profile') }}
                            </a>

                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                                {{ __('navigation.edit_profile') }}
                            </a>
                            
                            @role('Admin|Librarian')
                                <a href="{{ route('admin.home') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ __('navigation.admin') }} Panel
                                </a>
                            @endrole
                            
                            <!-- Language Switcher -->
                            <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
                            <div class="px-4 py-2">
                                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-2">Language</p>
                                <div class="space-y-1">
                                    <a href="{{ route('language.switch', 'en') }}" class="flex items-center px-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 rounded-lg {{ app()->getLocale() === 'en' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : '' }}">
                                        <span class="mr-3 text-lg">🇺🇸</span>
                                        <div>
                                            <div class="font-medium">English</div>
                                            <div class="text-xs text-gray-500">{{ __('navigation.switch_to_english') }}</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('language.switch', 'bn') }}" class="flex items-center px-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 rounded-lg {{ app()->getLocale() === 'bn' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : '' }}">
                                        <span class="mr-3 text-lg">🇧🇩</span>
                                        <div>
                                            <div class="font-medium">বাংলা</div>
                                            <div class="text-xs text-gray-500">{{ __('navigation.switch_to_bangla') }}</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
                            
                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    {{ __('navigation.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

        </div>

        <!-- Admin Search Bar (Separate Row) -->
        @if(auth()->check() && auth()->user()->hasRole(['Admin', 'Librarian']))
        <div class="hidden md:block border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <div class="flex items-center justify-center py-4 px-6">
                <div class="w-full max-w-2xl" x-data="headerSearch()" @click.outside="clearSearch()">
                    <div class="relative group">
                        <input type="text"
                               x-model="query"
                               @input="search()"
                               @keydown.arrow-down="navigateDown()"
                               @keydown.arrow-up="navigateUp()"
                               @keydown.enter="selectResult()"
                               @keydown.escape="clearSearch()"
                               placeholder="{{ __('filters.search') }} books, authors, {{ __('filters.isbn') }}..."
                               class="w-full px-4 py-2 pl-10 pr-10 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:border-blue-400 dark:focus:ring-blue-400/20 transition-all duration-200">

                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-4 h-4 text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <div x-show="query.length > 0" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <button @click="clearSearch()" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div x-show="query.length === 0" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <kbd class="px-1.5 py-0.5 text-xs font-medium text-gray-400 bg-gray-200 dark:bg-gray-600 dark:text-gray-500 rounded border border-gray-300 dark:border-gray-500">⌘K</kbd>
                        </div>

                        <div x-show="isSearching" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-4 h-4 text-blue-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>

                        <!-- Search Results Dropdown -->
                        <div x-show="results.length > 0"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 transform scale-95 translate-y-2"
                             class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-80 overflow-y-auto backdrop-blur-sm">
                            
                            <template x-for="(book, index) in results" :key="book.id">
                                <div class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 border-b border-gray-100 dark:border-gray-700 last:border-b-0"
                                     :class="{ 'bg-blue-50 dark:bg-blue-900/20': selectedIndex === index }">
                                    <div class="flex-shrink-0 w-10 h-14 bg-gray-100 dark:bg-gray-700 rounded-md flex items-center justify-center mr-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate" x-text="book.title"></h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="book.primary_author?.name || 'Unknown Author'"></p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500" x-text="book.category?.name"></p>
                                    </div>
                                    <div class="flex-shrink-0 ml-2 flex space-x-1">
                                        <!-- Edit Button (Primary) -->
                                        <a :href="`/admin/books/${book.id}/edit`" 
                                           class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors duration-200"
                                           @click.stop>
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <!-- View Button (Secondary) -->
                                        <a :href="`/books/${book.id}`" 
                                           class="inline-flex items-center px-2 py-1 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-md transition-colors duration-200"
                                           @click.stop>
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                    </div>
                                </div>
                            </template>

                            <!-- No Results -->
                            <div x-show="results.length === 0 && query.length > 0" class="px-4 py-6 text-center">
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">No books found</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Press Enter to search all books</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-cloak x-show="open" class="md:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <!-- Mobile Search Bar -->
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <div x-data="headerSearch()" @click.outside="clearSearch()">
                <div class="relative">
                    <input type="text"
                           x-model="query"
                           @input="search()"
                           @keydown.arrow-down="navigateDown()"
                           @keydown.arrow-up="navigateUp()"
                           @keydown.enter="selectResult()"
                           @keydown.escape="clearSearch()"
                           placeholder="{{ __('filters.search') }} books, authors, {{ __('filters.isbn') }}..."
                           class="w-full px-4 py-3 pl-10 pr-10 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:border-blue-400 dark:focus:ring-blue-400/20">

                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <div x-show="query.length > 0" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <button @click="clearSearch()" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div x-show="isSearching" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="w-5 h-5 text-blue-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>

                    <!-- Mobile Search Results -->
                    <div x-show="results.length > 0"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 transform scale-95 translate-y-2"
                         class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-64 overflow-y-auto">
                        <template x-for="(book, index) in results" :key="book.id">
                            <div @click="selectBook(book)"
                                 :class="{ 'bg-blue-50 dark:bg-blue-900/20': selectedIndex === index, 'hover:bg-gray-50 dark:hover:bg-gray-700': selectedIndex !== index }"
                                 class="flex items-start p-3 border-b border-gray-100 dark:border-gray-700 cursor-pointer transition-all duration-200 last:border-b-0">
                                <div class="flex-shrink-0 w-8 h-10 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900 dark:to-indigo-900 rounded flex items-center justify-center mr-3">
                                    <template x-if="book.cover_image">
                                        <img :src="book.cover_image" :alt="book.title" class="w-full h-full object-cover rounded">
                                    </template>
                                    <template x-if="!book.cover_image">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </template>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate" x-text="book.title"></h3>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 truncate" x-text="book.primary_author?.name || 'Unknown {{ __('filters.author') }}'"></p>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 mt-1" x-text="book.status"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Mobile No Results -->
                    <div x-show="query && results.length === 0 && !isSearching"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 transform scale-95 translate-y-2"
                         class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 text-center">
                        <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">No books found</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Press Enter to search all books</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="px-4 py-3 space-y-1">
            <!-- Main Navigation -->
            <a href="{{ route('home') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700' }}">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Browse Books
            </a>
            
            @auth
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    {{ __('navigation.my_dashboard') }}
                </a>
                
                @role('Admin|Librarian')
                    <div class="px-3 py-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">
                        {{ __('navigation.admin_menu') }}
                    </div>
                    
                    <!-- Books Dropdown -->
                    <div class="space-y-1">
                        <button @click="booksOpen = !booksOpen" class="flex items-center justify-between w-full px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Books
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': booksOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-cloak x-show="booksOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="ml-6 space-y-1">
                            <a href="{{ route('admin.books.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg">
                                <svg class="w-3 h-3 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Manage Books
                            </a>
                            <a href="{{ route('admin.books.create') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg">
                                <svg class="w-3 h-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add New Book
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg">
                                <svg class="w-3 h-3 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                {{ __('navigation.categories') }}
                            </a>
                            <a href="{{ route('admin.tags.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg">
                                <svg class="w-3 h-3 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                {{ __('navigation.tags') }}
                            </a>
                        </div>
                    </div>

                    <!-- {{ __('navigation.contributors') }} Dropdown -->
                    <div class="space-y-1">
                        <button @click="contributorsOpen = !contributorsOpen" class="flex items-center justify-between w-full px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ __('navigation.contributors') }}
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': contributorsOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-cloak x-show="contributorsOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="ml-6 space-y-1">
                            <a href="{{ route('admin.authors.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg">
                                <svg class="w-3 h-3 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ __('navigation.authors') }}
                            </a>
                            <a href="{{ route('admin.authors.create') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg">
                                <svg class="w-3 h-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('navigation.add_author') }}
                            </a>
                            <a href="{{ route('admin.publishers.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg">
                                <svg class="w-3 h-3 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ __('navigation.publishers') }}
                            </a>
                            <a href="{{ route('admin.publishers.create') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg">
                                <svg class="w-3 h-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('navigation.add_publisher') }}
                            </a>
                            <a href="{{ route('admin.languages.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg">
                                <svg class="w-3 h-3 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                                </svg>
                                {{ __('navigation.languages') }}
                            </a>
                        </div>
                    </div>

                    <!-- People/{{ __('navigation.loans') }} -->
                    <div class="space-y-1">
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg">
                            <svg class="w-3 h-3 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            {{ __('navigation.user_management') }}
                        </a>
                        <a href="{{ route('admin.loans.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg">
                            <svg class="w-3 h-3 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            {{ __('navigation.loan_management') }}
                        </a>
                    </div>

                    <!-- {{ __('navigation.admin') }} Panel -->
                    <a href="{{ route('admin.home') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg">
                        <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ __('navigation.admin') }} Panel
                    </a>
                @endrole
            @endauth
        </div>

        <!-- Mobile User Menu -->
        @auth
            <div class="border-t border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900">
                <div class="px-4 py-3">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <div class="px-4 py-2 space-y-1">
                    <!-- User Navigation Items -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        {{ __('navigation.dashboard') }}
                    </a>

                    <a href="{{ route('profile.show') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ __('navigation.my_profile') }}
                    </a>

                    <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        {{ __('navigation.edit_profile') }}
                    </a>

                    @role('Admin|Librarian')
                        <a href="{{ route('admin.home') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ __('navigation.admin') }} Panel
                        </a>
                    @endrole

                    <!-- Language Switcher -->
                    <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
                    <div class="px-3 py-2">
                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-2">Language</p>
                        <div class="space-y-1">
                            <a href="{{ route('language.switch', 'en') }}" class="flex items-center px-2 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg {{ app()->getLocale() === 'en' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : '' }}">
                                <span class="mr-3 text-lg">🇺🇸</span>
                                <div>
                                    <div class="font-medium">English</div>
                                    <div class="text-xs text-gray-500">{{ __('navigation.switch_to_english') }}</div>
                                </div>
                            </a>
                            <a href="{{ route('language.switch', 'bn') }}" class="flex items-center px-2 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 rounded-lg {{ app()->getLocale() === 'bn' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : '' }}">
                                <span class="mr-3 text-lg">🇧🇩</span>
                                <div>
                                    <div class="font-medium">বাংলা</div>
                                    <div class="text-xs text-gray-500">{{ __('navigation.switch_to_bangla') }}</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
                    
                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            {{ __('navigation.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="border-t border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900">
                <div class="px-4 py-3 space-y-2">
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700">
                        {{ __('navigation.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 rounded-lg">
                        {{ __('navigation.register') }}
                    </a>
                </div>
            </div>
        @endauth
    </div>
</nav>

<!-- Command Palette -->
<x-command-palette />

<script>
function headerSearch() {
    return {
        query: '',
        results: [],
        selectedIndex: -1,
        searchTimeout: null,
        isSearching: false,

        init() {
            // Add keyboard shortcut listener
            document.addEventListener('keydown', (e) => {
                if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                    e.preventDefault();
                    this.$refs.searchInput?.focus();
                }
            });
        },

        search() {
            // Use proper character counting for multi-byte characters like Bangla
            const charCount = Array.from(this.query).length;
            if (charCount < 2) {
                this.results = [];
                this.selectedIndex = -1;
                return;
            }

            // Clear previous timeout
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            this.isSearching = true;

            // Debounce search
            this.searchTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(`/api/search/books?q=${encodeURIComponent(this.query)}`);
                    const data = await response.json();
                    this.results = data.books || [];
                    this.selectedIndex = -1;
                } catch (error) {
                    console.error('Search error:', error);
                    this.results = [];
                } finally {
                    this.isSearching = false;
                }
            }, 300);
        },

        navigateDown() {
            if (this.results.length === 0) return;
            this.selectedIndex = Math.min(this.selectedIndex + 1, this.results.length - 1);
        },

        navigateUp() {
            if (this.results.length === 0) return;
            this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
        },

        selectResult() {
            if (this.selectedIndex >= 0 && this.results[this.selectedIndex]) {
                // For admin users, default to edit action
                @if(auth()->check() && auth()->user()->hasRole(['Admin', 'Librarian']))
                window.location.href = `/admin/books/${this.results[this.selectedIndex].id}/edit`;
                @else
                this.selectBook(this.results[this.selectedIndex]);
                @endif
            } else if (this.query.trim()) {
                // If no result selected but there's a query, perform full search
                this.performFullSearch();
            }
        },

        selectBook(book) {
            // Navigate to book details
            window.location.href = `/books/${book.id}`;
        },

        performFullSearch() {
            // Navigate to home page with search query
            const url = new URL(window.location.origin + '/');
            url.searchParams.set('search', this.query);
            window.location.href = url.toString();
        },

        clearSearch() {
            this.query = '';
            this.results = [];
            this.selectedIndex = -1;
        }
    }
}
</script>