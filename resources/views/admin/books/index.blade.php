<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Books</h2>
            <a href="{{ route('admin.books.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200 text-center">Add Book</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filters -->
                <div class="lg:w-1/4">
                    <!-- Mobile Filters Dropdown -->
                    <div x-data="{ open: false }" class="lg:hidden mb-4">
                        <button @click="open = !open" class="w-full inline-flex items-center justify-between px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                            <span class="flex items-center gap-2 text-gray-800 dark:text-gray-200 font-semibold">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                Filters
                            </span>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-transition class="mt-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <form method="GET" action="{{ route('admin.books.index') }}" class="p-4 space-y-6">
                                @if(request('q'))
                                    <input type="hidden" name="q" value="{{ request('q') }}">
                                @endif
                                <!-- Category -->
                                <div>
                                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Language -->
                                <div>
                                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Language</label>
                                    <select name="language" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">All Languages</option>
                                        @foreach($languages as $language)
                                            <option value="{{ $language->code }}" {{ request('language') == $language->code ? 'selected' : '' }}>{{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Year -->
                                <div>
                                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Publication Year</label>
                                    <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">All Years</option>
                                        @foreach($years as $year)
                                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Availability -->
                                <div>
                                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Availability</label>
                                    <select name="availability" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="all" {{ !request('availability') || request('availability') == 'all' ? 'selected' : '' }}>All Books (Default)</option>
                                        <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available Only</option>
                                        <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Unavailable Only</option>
                                    </select>
                                </div>
                                <!-- Banglish -->
                                <div>
                                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Banglish</label>
                                    <input type="text" name="banglish" value="{{ request('banglish') }}" placeholder="e.g. Shesher Kobita" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" />
                                </div>
                                <!-- ISBN -->
                                <div>
                                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ISBN</label>
                                    <input type="text" name="isbn" value="{{ request('isbn') }}" placeholder="ISBN" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white" />
                                </div>
                                <!-- Sort -->
                                <div>
                                    <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort By</label>
                                    <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                                        <option value="author" {{ request('sort') == 'author' ? 'selected' : '' }}>Author A-Z</option>
                                        <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Year (Newest)</option>
                                    </select>
                                </div>
                                <div class="flex gap-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">Apply</button>
                                    <a href="{{ route('admin.books.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-center transition">Clear</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Search Field -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <svg class="w-4 h-4 inline mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search Books
                        </label>
                        <div class="relative">
                            <input type="text" name="q" id="q" value="{{ request('q') }}" 
                                   placeholder="Search title/author (EN/BN)" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" 
                                   autocomplete="off" />
                            <div id="suggestions" class="absolute z-10 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg mt-1 hidden shadow-lg max-h-60 overflow-y-auto"></div>
                        </div>
                    </div>

                    <!-- Current Filters Display -->
                    @if(request()->hasAny(['q', 'category', 'language', 'year', 'availability', 'banglish', 'isbn', 'sort']) && (request('sort') != 'latest' || request('availability') != 'all' || request()->hasAny(['q', 'category', 'language', 'year', 'banglish', 'isbn'])))
                        <div class="hidden lg:block bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-white flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                    </svg>
                                    Active Filters
                                </h3>
                                <a href="{{ route('admin.books.index') }}" class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    Clear All
                                </a>
                            </div>
                            <div class="space-y-2">
                                @if(request('q'))
                                    <div class="flex items-center justify-between bg-blue-50 dark:bg-blue-900/20 rounded-md px-3 py-2">
                                        <span class="text-sm text-blue-800 dark:text-blue-200">Search: "{{ request('q') }}"</span>
                                        <a href="{{ route('admin.books.index', request()->except('q')) }}" class="text-blue-600 hover:text-blue-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                @if(request('category'))
                                    @php
                                        $selectedCategory = $categories->where('id', request('category'))->first();
                                    @endphp
                                    @if($selectedCategory)
                                        <div class="flex items-center justify-between bg-green-50 dark:bg-green-900/20 rounded-md px-3 py-2">
                                            <span class="text-sm text-green-800 dark:text-green-200">Category: {{ $selectedCategory->name_en }}</span>
                                            <a href="{{ route('admin.books.index', request()->except('category')) }}" class="text-green-600 hover:text-green-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    @endif
                                @endif
                                @if(request('language'))
                                    @php
                                        $selectedLanguage = $languages->where('code', request('language'))->first();
                                    @endphp
                                    @if($selectedLanguage)
                                        <div class="flex items-center justify-between bg-purple-50 dark:bg-purple-900/20 rounded-md px-3 py-2">
                                            <span class="text-sm text-purple-800 dark:text-purple-200">Language: {{ $selectedLanguage->name }}</span>
                                            <a href="{{ route('admin.books.index', request()->except('language')) }}" class="text-purple-600 hover:text-purple-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    @endif
                                @endif
                                @if(request('year'))
                                    <div class="flex items-center justify-between bg-orange-50 dark:bg-orange-900/20 rounded-md px-3 py-2">
                                        <span class="text-sm text-orange-800 dark:text-orange-200">Year: {{ request('year') }}</span>
                                        <a href="{{ route('admin.books.index', request()->except('year')) }}" class="text-orange-600 hover:text-orange-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                @if(request('availability') && request('availability') != 'all')
                                    <div class="flex items-center justify-between bg-indigo-50 dark:bg-indigo-900/20 rounded-md px-3 py-2">
                                        <span class="text-sm text-indigo-800 dark:text-indigo-200">Availability: {{ ucfirst(request('availability')) }}</span>
                                        <a href="{{ route('admin.books.index', request()->except('availability')) }}" class="text-indigo-600 hover:text-indigo-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                @if(request('banglish'))
                                    <div class="flex items-center justify-between bg-orange-50 dark:bg-orange-900/20 rounded-md px-3 py-2">
                                        <span class="text-sm text-orange-800 dark:text-orange-200">Banglish: "{{ request('banglish') }}"</span>
                                        <a href="{{ route('admin.books.index', request()->except('banglish')) }}" class="text-orange-600 hover:text-orange-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                @if(request('isbn'))
                                    <div class="flex items-center justify-between bg-teal-50 dark:bg-teal-900/20 rounded-md px-3 py-2">
                                        <span class="text-sm text-teal-800 dark:text-teal-200">ISBN: "{{ request('isbn') }}"</span>
                                        <a href="{{ route('admin.books.index', request()->except('isbn')) }}" class="text-teal-600 hover:text-teal-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                                @if(request('sort') && request('sort') != 'latest')
                                    <div class="flex items-center justify-between bg-pink-50 dark:bg-pink-900/20 rounded-md px-3 py-2">
                                        <span class="text-sm text-pink-800 dark:text-pink-200">Sort: {{ ucfirst(str_replace('_', ' ', request('sort'))) }}</span>
                                        <a href="{{ route('admin.books.index', request()->except('sort')) }}" class="text-pink-600 hover:text-pink-800">
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
                                Filters
                            </h3>
                        </div>
                        
                        <form method="GET" action="{{ route('admin.books.index') }}" class="p-4 space-y-6">
                            <!-- Preserve search parameter -->
                            @if(request('q'))
                                <input type="hidden" name="q" value="{{ request('q') }}">
                            @endif
                            
                            <!-- Category Filter -->
                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Category
                                </label>
                                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition duration-200">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Language Filter -->
                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                                    </svg>
                                    Language
                                </label>
                                <select name="language" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition duration-200">
                                    <option value="">All Languages</option>
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
                                    Publication Year
                                </label>
                                <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition duration-200">
                                    <option value="">All Years</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Availability Filter -->
                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Availability
                                </label>
                                <select name="availability" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition duration-200">
                                    <option value="all" {{ !request('availability') || request('availability') == 'all' ? 'selected' : '' }}>
                                        All Books (Default)
                                    </option>
                                    <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>
                                        Available Only
                                    </option>
                                    <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>
                                        Unavailable Only
                                    </option>
                                </select>
                            </div>

                            <!-- Banglish Filter -->
                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                                    </svg>
                                    Banglish
                                </label>
                                <input type="text" name="banglish" value="{{ request('banglish') }}" 
                                       placeholder="e.g. Shesher Kobita" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white" />
                            </div>

                            <!-- ISBN Filter -->
                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    ISBN
                                </label>
                                <input type="text" name="isbn" value="{{ request('isbn') }}" 
                                       placeholder="ISBN" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white" />
                            </div>

                            <!-- Sort Options -->
                            <div>
                                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                    Sort By
                                </label>
                                <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition duration-200">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                                    <option value="author" {{ request('sort') == 'author' ? 'selected' : '' }}>Author A-Z</option>
                                    <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Year (Newest)</option>
                                </select>
                            </div>

                            <div class="flex space-x-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                                    </svg>
                                    Apply
                                </button>
                                <a href="{{ route('admin.books.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
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
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 sm:p-6 text-gray-900 dark:text-white">
                    <!-- Responsive Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title (EN)</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">Title (BN)</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">Category</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">ISBN</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($books as $book)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ Str::limit($book->title_en, 30) }}</div>
                                            @if($book->title_bn)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 sm:hidden">{{ Str::limit($book->title_bn, 25) }}</div>
                                            @endif
                                            <div class="text-xs text-gray-500 dark:text-gray-400 md:hidden">{{ optional($book->category)->name_en }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 lg:hidden">{{ $book->isbn }}</div>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden sm:table-cell">
                                            {{ Str::limit($book->title_bn, 30) }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden md:table-cell">
                                            {{ optional($book->category)->name_en }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden lg:table-cell">
                                            {{ $book->isbn }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-col sm:flex-row gap-2">
                                                <a href="{{ route('admin.books.edit',$book) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-xs sm:text-sm">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.books.destroy',$book) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-xs sm:text-sm" 
                                                            onclick="return confirm('Delete this book?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                            <div class="mt-4">{{ $books->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const q = document.getElementById('q');
  const box = document.getElementById('suggestions');
  const baseUrl = "{{ route('admin.books.index') }}";
  let timer;

  function navigateWithQuery(newQuery){
    const params = new URLSearchParams(window.location.search);
    if(newQuery){
      params.set('q', newQuery);
    }else{
      params.delete('q');
    }
    const url = baseUrl + (params.toString() ? ('?' + params.toString()) : '');
    window.location.replace(url);
  }

  q.addEventListener('input', function(){
    clearTimeout(timer);
    const val = q.value.trim();
    if(!val){
      box.classList.add('hidden');
      box.innerHTML='';
      // Remove q from URL and reload after short debounce
      timer = setTimeout(() => navigateWithQuery(''), 300);
      return;
    }
    // Debounced live filter by navigating with updated q
    timer = setTimeout(() => navigateWithQuery(val), 400);

    // Fetch suggestions (non-blocking)
    (async () => {
      try{
        const res = await fetch('{{ route('suggest.books') }}?q=' + encodeURIComponent(val), {headers:{'X-Requested-With':'XMLHttpRequest'}});
        const data = await res.json();
        box.innerHTML = data.map(d => `<button type="button" class=\"w-full text-left px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer text-sm text-gray-700 dark:text-gray-200\">${(d.title_en || '')} ${(d.title_bn ? '/ ' + d.title_bn : '')}</button>`).join('');
        box.classList.remove('hidden');
      }catch(e){
        // swallow
      }
    })();
  });

  box.addEventListener('click', function(e){
    const btn = e.target.closest('button');
    if(!btn) return;
    const text = btn.textContent.trim();
    q.value = text;
    navigateWithQuery(text);
  });

  document.addEventListener('click', function(e){ if(!box.contains(e.target) && e.target !== q){ box.classList.add('hidden'); }});
});
</script>
