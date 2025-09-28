<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['books' => []]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['books' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div x-data="commandPalette()" x-show="isOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="close()"></div>
    
    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl transform transition-all" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <!-- Search Container -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Search Input -->
                <div class="flex items-center px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input 
                        x-model="query" 
                        @input="search()"
                        @keydown.arrow-down="navigateDown()"
                        @keydown.arrow-up="navigateUp()"
                        @keydown.enter="selectResult()"
                        @keydown.escape="close()"
                        type="text" 
                        placeholder="Search books, authors, categories..." 
                        class="flex-1 text-lg bg-transparent border-none outline-none text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                        autofocus
                    >
                    <div class="flex items-center space-x-2 ml-3">
                        <kbd class="px-2 py-1 text-xs font-semibold text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 rounded">Esc</kbd>
                    </div>
                </div>

                <!-- Search Results -->
                <div class="max-h-96 overflow-y-auto" x-show="results.length > 0">
                    <template x-for="(book, index) in results" :key="book.id">
                        <div 
                            @click="selectBook(book)"
                            :class="{
                                'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800': selectedIndex === index,
                                'hover:bg-gray-50 dark:hover:bg-gray-700': selectedIndex !== index
                            }"
                            class="flex items-center p-4 border-l-4 border-transparent cursor-pointer transition-colors duration-150"
                        >
                            <!-- Book Cover -->
                            <div class="flex-shrink-0 w-12 h-16 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900 dark:to-indigo-900 rounded-md flex items-center justify-center mr-4">
                                <template x-if="book.cover_image">
                                    <img :src="book.cover_image" :alt="book.title" class="w-full h-full object-cover rounded-md">
                                </template>
                                <template x-if="!book.cover_image">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </template>
                            </div>

                            <!-- Book Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate" x-text="book.title"></h3>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200" x-text="book.status"></span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400" x-text="book.publication_year"></span>
                                    </div>
                                </div>
                                
                                <div class="mt-1 flex items-center space-x-2">
                                    <p class="text-sm text-gray-600 dark:text-gray-300 truncate" x-text="book.primary_author?.name || 'Unknown Author'"></p>
                                    <span class="text-gray-400">•</span>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate" x-text="book.publisher?.name || 'Unknown Publisher'"></p>
                                </div>
                                
                                <div class="mt-1 flex items-center space-x-2">
                                    <template x-if="book.category">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200" x-text="book.category.name"></span>
                                    </template>
                                    <template x-for="tag in book.tags" :key="tag.id">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200" x-text="tag.name"></span>
                                    </template>
                                </div>
                                
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 line-clamp-2" x-text="book.description || 'No description available'"></p>
                            </div>

                            <!-- Arrow Icon -->
                            <div class="flex-shrink-0 ml-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- No Results -->
                <div x-show="query && results.length === 0" class="p-8 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No books found</h3>
                    <p class="text-gray-500 dark:text-gray-400">Try searching with different keywords or check the spelling.</p>
                </div>

                <!-- Empty State -->
                <div x-show="!query" class="p-8 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Search Library</h3>
                    <p class="text-gray-500 dark:text-gray-400">Start typing to search for books, authors, or categories.</p>
                    <div class="mt-4 flex justify-center space-x-4 text-sm text-gray-400">
                        <span>Press <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">↑↓</kbd> to navigate</span>
                        <span>Press <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">Enter</kbd> to select</span>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex items-center space-x-4">
                        <span>Press <kbd class="px-1 py-0.5 bg-gray-200 dark:bg-gray-700 rounded">Esc</kbd> to close</span>
                    </div>
                    <div x-show="results.length > 0" class="text-right">
                        <span x-text="results.length"></span> result<span x-show="results.length !== 1">s</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function commandPalette() {
    return {
        isOpen: false,
        query: '',
        results: [],
        selectedIndex: -1,
        searchTimeout: null,

        init() {
            // Listen for keyboard shortcut (Cmd/Ctrl + K) - only if not on home page
            document.addEventListener('keydown', (e) => {
                if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                    // Don't open command palette if we're on home page (let inline search handle it)
                    if (window.location.pathname === '/') {
                        return;
                    }
                    e.preventDefault();
                    this.open();
                }
            });

            // Close on escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.close();
                }
            });

            // Listen for open-search event
            window.addEventListener('open-search', () => {
                this.open();
            });
        },

        open() {
            this.isOpen = true;
            this.query = '';
            this.results = [];
            this.selectedIndex = -1;
            // Focus will be handled by autofocus on input
        },

        close() {
            this.isOpen = false;
            this.query = '';
            this.results = [];
            this.selectedIndex = -1;
        },

        async search() {
            if (this.query.length < 2) {
                this.results = [];
                this.selectedIndex = -1;
                return;
            }

            // Clear previous timeout
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

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
            // Navigate to book details or perform action
            window.location.href = `/books/${book.id}`;
        }
    }
}
</script>
<?php /**PATH /var/www/html/resources/views/components/command-palette.blade.php ENDPATH**/ ?>