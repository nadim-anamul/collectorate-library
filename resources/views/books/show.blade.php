<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $book->title_en ?: $book->title_bn }}</h1>
            <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                ← Back to Library
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="md:flex">
                    <!-- Book Cover -->
                    <div class="md:w-1/3 p-6">
                        <div class="w-full h-96 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900 dark:to-indigo-900 rounded-lg flex items-center justify-center">
                            @if($book->cover_path)
                                <img src="{{ $book->cover_path }}" alt="{{ $book->title_en ?: $book->title_bn }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <svg class="w-24 h-24 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <!-- Book Details -->
                    <div class="md:w-2/3 p-6">
                        <div class="space-y-4">
                            <!-- Title -->
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ $book->title_en ?: $book->title_bn }}
                                </h2>
                                @if($book->title_bn && $book->title_en)
                                    <p class="text-xl text-gray-600 dark:text-gray-300 mt-2">{{ $book->title_bn }}</p>
                                @endif
                            </div>

                            <!-- Author -->
                            @if($book->primaryAuthor)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Author</h3>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        {{ $book->primaryAuthor->name_en ?: $book->primaryAuthor->name_bn }}
                                        @if($book->primaryAuthor->name_bn && $book->primaryAuthor->name_en)
                                            ({{ $book->primaryAuthor->name_bn }})
                                        @endif
                                    </p>
                                </div>
                            @endif

                            <!-- Publisher -->
                            @if($book->publisher)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Publisher</h3>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        {{ $book->publisher->name_en ?: $book->publisher->name_bn }}
                                        @if($book->publisher->name_bn && $book->publisher->name_en)
                                            ({{ $book->publisher->name_bn }})
                                        @endif
                                    </p>
                                </div>
                            @endif

                            <!-- ISBN -->
                            @if($book->isbn)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">ISBN</h3>
                                    <p class="text-gray-600 dark:text-gray-400 font-mono">{{ $book->isbn }}</p>
                                </div>
                            @endif

                            <!-- Publication Year -->
                            @if($book->publication_year)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Publication Year</h3>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $book->publication_year }}</p>
                                </div>
                            @endif

                            <!-- Language -->
                            @if($book->language)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Language</h3>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $book->language->name }}</p>
                                </div>
                            @endif

                            <!-- Category -->
                            @if($book->category)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Category</h3>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $book->category->name_en ?: $book->category->name_bn }}
                                    </span>
                                </div>
                            @endif

                            <!-- Tags -->
                            @if($book->tags->count() > 0)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Tags</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($book->tags as $tag)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                {{ $tag->name_en ?: $tag->name_bn }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Availability -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Availability</h3>
                                <div class="flex items-center space-x-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $book->available_copies > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $book->available_copies > 0 ? 'Available' : 'Unavailable' }}
                                    </span>
                                    <span class="text-gray-600 dark:text-gray-400">
                                        {{ $book->available_copies }} of {{ $book->total_copies }} copies available
                                    </span>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($book->description_en || $book->description_bn)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Description</h3>
                                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                        {{ $book->description_en ?: $book->description_bn }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
