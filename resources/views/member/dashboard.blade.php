<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
                    Welcome, {{ $user->name }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ ucfirst($user->member_type) }} Member • Member since {{ $user->created_at->format('M Y') }}
                </p>
            </div>
            <div class="flex space-x-2">
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                    {{ $stats['current_loans'] }} Active Loans
                </span>
                @if($stats['overdue_books'] > 0)
                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">
                        {{ $stats['overdue_books'] }} Overdue
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-400 bg-opacity-20 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-blue-100">Current Loans</p>
                            <p class="text-3xl font-bold">{{ $stats['current_loans'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-400 bg-opacity-20 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-green-100">Total Borrowed</p>
                            <p class="text-3xl font-bold">{{ $stats['total_borrowed'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-400 bg-opacity-20 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-purple-100">Reading Status</p>
                            <p class="text-3xl font-bold">
                                @if($stats['overdue_books'] > 0)
                                    {{ $stats['overdue_books'] }} Overdue
                                @else
                                    Up to Date
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Current Loans -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Current Loans</h3>
                    </div>
                    <div class="p-6">
                        @if(!$member)
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-blue-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Member Profile Needed</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">To borrow books, you need to complete your member profile. Please contact the librarian to set up your library membership.</p>
                            </div>
                        @else
                            @forelse($currentLoans as $loan)
                            <div class="flex items-center space-x-4 py-4 {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-700' : '' }}">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded flex items-center justify-center">
                                        @if($loan->book->cover_path)
                                            <img src="{{ Storage::url($loan->book->cover_path) }}" alt="{{ $loan->book->title_en }}" class="w-full h-full object-cover rounded">
                                        @else
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        @if($loan->book->language && $loan->book->language->code === 'bn' && $loan->book->title_bn)
                                            {{ $loan->book->title_bn }}
                                        @else
                                            {{ $loan->book->title_en }}
                                        @endif
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        @if($loan->book->authors->count() > 0)
                                            @foreach($loan->book->authors as $author)
                                                @if($loan->book->language && $loan->book->language->code === 'bn' && $author->name_bn)
                                                    {{ $author->name_bn }}
                                                @else
                                                    {{ $author->name_en }}
                                                @endif
                                                @if(!$loop->last), @endif
                                            @endforeach
                                        @else
                                            @if($loan->book->language && $loan->book->language->code === 'bn' && $loan->book->author_bn)
                                                {{ $loan->book->author_bn }}
                                            @else
                                                {{ $loan->book->author_en ?: 'Unknown Author' }}
                                            @endif
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                        Due: {{ \Carbon\Carbon::parse($loan->due_at)->format('M d, Y') }}
                                        @if(\Carbon\Carbon::parse($loan->due_at) < now())
                                            <span class="text-red-500 font-medium">(Overdue)</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No current loans</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">Browse our collection to borrow your first book!</p>
                                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                                    Browse Books
                                </a>
                            </div>
                        @endforelse
                        @endif
                    </div>
                </div>

                <!-- Reading History -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Reading History</h3>
                    </div>
                    <div class="p-6">
                        @forelse($loanHistory as $loan)
                            <div class="flex items-center space-x-4 py-4 {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-700' : '' }}">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded flex items-center justify-center">
                                        @if($loan->book->cover_path)
                                            <img src="{{ Storage::url($loan->book->cover_path) }}" alt="{{ $loan->book->title_en }}" class="w-full h-full object-cover rounded">
                                        @else
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        @if($loan->book->language && $loan->book->language->code === 'bn' && $loan->book->title_bn)
                                            {{ $loan->book->title_bn }}
                                        @else
                                            {{ $loan->book->title_en }}
                                        @endif
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        @if($loan->book->authors->count() > 0)
                                            @foreach($loan->book->authors as $author)
                                                @if($loan->book->language && $loan->book->language->code === 'bn' && $author->name_bn)
                                                    {{ $author->name_bn }}
                                                @else
                                                    {{ $author->name_en }}
                                                @endif
                                                @if(!$loop->last), @endif
                                            @endforeach
                                        @else
                                            @if($loan->book->language && $loan->book->language->code === 'bn' && $loan->book->author_bn)
                                                {{ $loan->book->author_bn }}
                                            @else
                                                {{ $loan->book->author_en ?: 'Unknown Author' }}
                                            @endif
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                        Returned: {{ \Carbon\Carbon::parse($loan->returned_at)->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No reading history</h3>
                                <p class="text-gray-600 dark:text-gray-400">Your completed books will appear here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recommended Books -->
            @if($recommendedBooks->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recommended for You</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Books you might enjoy based on your reading preferences</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($recommendedBooks as $book)
                                <div class="group cursor-pointer">
                                    <div class="aspect-[3/4] bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 rounded-lg overflow-hidden mb-3 group-hover:shadow-lg transition-shadow duration-200">
                                        @if($book->cover_path)
                                            <img src="{{ Storage::url($book->cover_path) }}" alt="{{ $book->title_en }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <h4 class="font-medium text-gray-900 dark:text-white text-sm mb-1 line-clamp-2">
                                        @if($book->language && $book->language->code === 'bn' && $book->title_bn)
                                            {{ $book->title_bn }}
                                        @else
                                            {{ $book->title_en }}
                                        @endif
                                    </h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                                        @if($book->authors->count() > 0)
                                            @foreach($book->authors as $author)
                                                @if($book->language && $book->language->code === 'bn' && $author->name_bn)
                                                    {{ $author->name_bn }}
                                                @else
                                                    {{ $author->name_en }}
                                                @endif
                                                @if(!$loop->last), @endif
                                            @endforeach
                                        @else
                                            @if($book->language && $book->language->code === 'bn' && $book->author_bn)
                                                {{ $book->author_bn }}
                                            @else
                                                {{ $book->author_en ?: 'Unknown Author' }}
                                            @endif
                                        @endif
                                    </p>
                                    @if($book->category)
                                        <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded-full">
                                            @if($book->language && $book->language->code === 'bn' && $book->category->name_bn)
                                                {{ $book->category->name_bn }}
                                            @else
                                                {{ $book->category->name_en }}
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6 text-center">
                            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                                Browse All Books
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
