<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Book Library</h1>
            @auth
                <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    Dashboard
                </a>
            @else
                <div class="space-x-2">
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        Register
                    </a>
                </div>
            @endauth
        </div>
    </x-slot>

    <div class="py-6">
        <!-- Search Bar - Full width with proper container -->
        <div class="mb-8" x-data="homeSearch()" @click.outside="clearSearch()">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Search Input - Takes up 1/3 of the width to align with sidebar -->
                    <div class="lg:w-1/4">
                        <div class="relative">
                            <input type="text" 
                                   x-model="query"
                                   @input="search()"
                                   @keydown.arrow-down="navigateDown()"
                                   @keydown.arrow-up="navigateUp()"
                                   @keydown.enter="selectResult()"
                                   @keydown.escape="clearSearch()"
                                   placeholder="Search books..."
                                   class="w-full px-4 py-3 pl-12 pr-4 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white dark:border-gray-600">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <kbd class="px-2 py-1 text-xs font-semibold text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 rounded">⌘K</kbd>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Spacer to align with main content -->
                    <div class="lg:w-3/4"></div>
                </div>

                <!-- Search Results Dropdown -->
                <div x-show="results.length > 0" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute z-50 w-full lg:w-1/4 mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-96 overflow-y-auto">
                        <template x-for="(book, index) in results" :key="book.id">
                            <div 
                                @click="selectBook(book)"
                                :class="{
                                    'bg-blue-50 dark:bg-blue-900/20': selectedIndex === index,
                                    'hover:bg-gray-50 dark:hover:bg-gray-700': selectedIndex !== index
                                }"
                                class="flex items-start sm:items-center p-3 sm:p-4 border-b border-gray-100 dark:border-gray-700 cursor-pointer transition-colors duration-150 last:border-b-0"
                            >
                                <!-- Book Cover -->
                                <div class="flex-shrink-0 w-10 h-12 sm:w-12 sm:h-16 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900 dark:to-indigo-900 rounded-md flex items-center justify-center mr-3 sm:mr-4">
                                    <template x-if="book.cover_image">
                                        <img :src="book.cover_image" :alt="book.title" class="w-full h-full object-cover rounded-md">
                                    </template>
                                    <template x-if="!book.cover_image">
                                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </template>
                                </div>

                                <!-- Book Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate" x-text="book.title"></h3>
                                        <div class="flex items-center space-x-2 mt-1 sm:mt-0">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200" x-text="book.status"></span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="book.publication_year"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-1 flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-2">
                                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 truncate" x-text="book.primary_author?.name || 'Unknown Author'"></p>
                                        <span class="hidden sm:inline text-gray-400">•</span>
                                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate" x-text="book.publisher?.name || 'Unknown Publisher'"></p>
                                    </div>
                                    
                                    <div class="mt-1 flex flex-wrap items-center gap-1 sm:gap-2">
                                        <template x-if="book.category">
                                            <span class="inline-flex items-center px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200" x-text="book.category.name"></span>
                                        </template>
                                        <template x-for="tag in book.tags" :key="tag.id">
                                            <span class="inline-flex items-center px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200" x-text="tag.name"></span>
                                        </template>
                                    </div>
                                    
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 line-clamp-2 hidden sm:block" x-text="book.description || 'No description available'"></p>
                                </div>

                                <!-- Arrow Icon -->
                                <div class="flex-shrink-0 ml-1 sm:ml-2">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </template>
                    </div>

                <!-- No Results -->
                <div x-show="query && results.length === 0 && !isSearching" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute z-50 w-full lg:w-1/4 mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 text-center">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709"></path>
                        </svg>
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">No books found</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Try searching with different keywords.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content - Properly constrained width -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filters -->
                <div class="lg:w-1/4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Filters</h3>
                        
                        <form method="GET" action="{{ route('home') }}" class="space-y-6">
                            <!-- Preserve search parameter -->
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            
                            <!-- Category Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
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
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Language</label>
                                <select name="language" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">All Languages</option>
                                    @foreach($languages as $language)
                                        <option value="{{ $language }}" {{ request('language') == $language ? 'selected' : '' }}>
                                            {{ ucfirst($language) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Year Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Publication Year</label>
                                <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">All Years</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sort Options -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort By</label>
                                <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                                    <option value="author" {{ request('sort') == 'author' ? 'selected' : '' }}>Author A-Z</option>
                                    <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Year (Newest)</option>
                                </select>
                            </div>

                            <div class="flex space-x-2">
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                                    Apply Filters
                                </button>
                                <a href="{{ route('home') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200 text-center">
                                    Clear
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:w-3/4">
                    <!-- Results Header -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                                {{ $books->total() }} Books Found
                            </h2>
                            @if(request()->hasAny(['search', 'category', 'language', 'year']))
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Filtered results
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Books Grid -->
                    @if($books->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($books as $book)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition duration-200 overflow-hidden">
                                    <!-- Book Cover -->
                                    <div class="aspect-[3/4] bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        @if($book->cover_path)
                                            <img src="{{ Storage::url($book->cover_path) }}" 
                                                 alt="{{ $book->title_en }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="text-center text-gray-500 dark:text-gray-400">
                                                <svg class="w-16 h-16 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <p class="text-sm">No Cover</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Book Info -->
                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-800 dark:text-white mb-2 line-clamp-2">
                                            {{ $book->title_en }}
                                        </h3>
                                        
                                        @if($book->author_en)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                by {{ $book->author_en }}
                                            </p>
                                        @endif
                                        
                                        @if($book->category)
                                            <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded-full mb-2">
                                                {{ $book->category->name_en }}
                                            </span>
                                        @endif
                                        
                                        <div class="flex justify-between items-center mt-3">
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                @if($book->publication_year)
                                                    {{ $book->publication_year }}
                                                @endif
                                                @if($book->pages)
                                                    • {{ $book->pages }} pages
                                                @endif
                                            </div>
                                            <div class="text-sm font-medium text-green-600 dark:text-green-400">
                                                {{ $book->available_copies }} available
                                            </div>
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
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No books found</h3>
                            <p class="text-gray-600 dark:text-gray-400">Try adjusting your search criteria or filters.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function homeSearch() {
            return {
                query: '',
                results: [],
                selectedIndex: -1,
                searchTimeout: null,
                isSearching: false,

                search() {
                    if (this.query.length < 2) {
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
                        this.selectBook(this.results[this.selectedIndex]);
                    }
                },

                selectBook(book) {
                    // Navigate to book details
                    window.location.href = `/books/${book.id}`;
                },

                clearSearch() {
                    this.query = '';
                    this.results = [];
                    this.selectedIndex = -1;
                }
            }
        }
    </script>
</x-app-layout>
