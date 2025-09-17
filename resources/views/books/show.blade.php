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
                                <img src="{{ Storage::url($book->cover_path) }}" alt="{{ $book->title_en ?: $book->title_bn }}" class="w-full h-full object-cover rounded-lg">
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
                                
                                <!-- Borrow Request Button -->
                                @auth
                                    @if(Auth::user()->status === 'approved')
                                        @php
                                            $existingLoan = \App\Models\Models\Loan::where('user_id', auth()->id())
                                                ->where('book_id', $book->id)
                                                ->whereIn('status',["pending","issued"]) // active
                                                ->first();
                                        @endphp
                                        <div class="mt-4">
                                            @if($existingLoan)
                                                <div class="inline-flex items-center gap-3">
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                                        You have a {{ ucfirst($existingLoan->status) }} request for this book
                                                    </span>
                                                    <button class="px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed" disabled>
                                                        Request to Borrow
                                                    </button>
                                                </div>
                                            @else
                                                @if($book->available_copies > 0)
                                                    <form action="{{ route('books.request', $book) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                            </svg>
                                                            Request to Borrow
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed" disabled>
                                                        Not Available
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                Your account must be approved to request a borrow.
                                            </p>
                                        </div>
                                    @endif
                                @else
                                    <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                        <p class="text-sm text-blue-700 dark:text-blue-300">
                                            <a href="{{ route('login') }}" class="font-medium hover:underline">Sign in</a> to request this book.
                                        </p>
                                    </div>
                                @endauth
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
