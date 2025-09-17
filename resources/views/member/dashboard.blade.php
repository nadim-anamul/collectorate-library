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
        @if($stats['overdue_books'] > 0)
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
                <div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 dark:bg-red-900/20 dark:text-red-200 dark:border-red-800">
                    You have {{ $stats['overdue_books'] }} overdue {{ $stats['overdue_books'] === 1 ? 'book' : 'books' }}. Please return them as soon as possible.
                </div>
            </div>
        @endif
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
                                            @if($loan->book->primaryAuthor)
                                                @if($loan->book->language && $loan->book->language->code === 'bn' && $loan->book->primaryAuthor->name_bn)
                                                    {{ $loan->book->primaryAuthor->name_bn }}
                                                @else
                                                    {{ $loan->book->primaryAuthor->name_en }}
                                                @endif
                                            @else
                                                Unknown Author
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
                                <div class="flex-shrink-0">
                                    @if($loan->status === 'issued')
                                        <form action="{{ route('loans.requestReturn', $loan) }}" method="POST">@csrf
                                            <button class="px-3 py-2 text-sm bg-yellow-600 hover:bg-yellow-700 text-white rounded">Request Return</button>
                                        </form>
                                    @elseif($loan->status === 'return_requested')
                                        <span class="px-3 py-2 text-sm rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Return Requested</span>
                                    @endif
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
                    </div>
                </div>

                <!-- Pending Requests -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pending Requests</h3>
                    </div>
                    <div class="p-6">
                        @forelse($pendingRequests as $loan)
                            <div class="flex items-center space-x-4 py-4 {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-700' : '' }}">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-16 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded flex items-center justify-center">
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
                                            @if($loan->book->primaryAuthor)
                                                @if($loan->book->language && $loan->book->language->code === 'bn' && $loan->book->primaryAuthor->name_bn)
                                                    {{ $loan->book->primaryAuthor->name_bn }}
                                                @else
                                                    {{ $loan->book->primaryAuthor->name_en }}
                                                @endif
                                            @else
                                                Unknown Author
                                            @endif
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                        Requested: {{ \Carbon\Carbon::parse($loan->requested_at)->format('M d, Y') }}
                                        @if($loan->requested_due_at)
                                            • Requested Due: {{ \Carbon\Carbon::parse($loan->requested_due_at)->format('M d, Y') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="px-3 py-2 text-sm rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Pending</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No pending requests</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">Request a book from its details page to see it here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Reading History -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Reading History</h3>
                        <a href="{{ route('dashboard.history') }}" class="text-sm px-3 py-1 rounded border dark:border-gray-600">View all</a>
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
                                            @if($loan->book->primaryAuthor)
                                                @if($loan->book->language && $loan->book->language->code === 'bn' && $loan->book->primaryAuthor->name_bn)
                                                    {{ $loan->book->primaryAuthor->name_bn }}
                                                @else
                                                    {{ $loan->book->primaryAuthor->name_en }}
                                                @endif
                                            @else
                                                Unknown Author
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
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($recommendedBooks as $book)
                                <div class="group cursor-pointer bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700">
                                    <!-- Book Cover -->
                                    <div class="relative aspect-[4/5] bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 overflow-hidden">
                                        @if($book->cover_path)
                                            <img src="{{ Storage::url($book->cover_path) }}" alt="{{ $book->title_en }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <div class="text-center">
                                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                    </svg>
                                                    <p class="text-xs font-medium text-gray-400 dark:text-gray-500">No Cover</p>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <!-- Availability Badge -->
                                        <div class="absolute top-2 right-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Available
                                            </span>
                                        </div>
                                        
                                        <!-- Hover Overlay -->
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center">
                                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <a href="{{ route('books.show', $book->id) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Book Info -->
                                    <div class="p-4">
                                        <!-- Title -->
                                        <h3 class="font-semibold text-base text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                            @if($book->language && $book->language->code === 'bn' && $book->title_bn)
                                                {{ $book->title_bn }}
                                            @else
                                                {{ $book->title_en }}
                                            @endif
                                        </h3>
                                        
                                        <!-- Author -->
                                        @if($book->primaryAuthor)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 flex items-center">
                                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                @if($book->language && $book->language->code === 'bn' && $book->primaryAuthor->name_bn)
                                                    {{ $book->primaryAuthor->name_bn }}
                                                @else
                                                    {{ $book->primaryAuthor->name_en }}
                                                @endif
                                            </p>
                                        @elseif($book->primaryAuthor)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 flex items-center">
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
                                        
                                        <!-- Category -->
                                        @if($book->category)
                                            <div class="mb-3">
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
                                        
                                        <!-- Book Details -->
                                        <div class="flex flex-col space-y-1 text-xs text-gray-500 dark:text-gray-400">
                                            <!-- Publication Year -->
                                            @if($book->publication_year)
                                                <div class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span>{{ $book->publication_year }}</span>
                                                </div>
                                            @endif
                                            
                                            <!-- Pages -->
                                            @if($book->pages)
                                                <div class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                    </svg>
                                                    <span>{{ $book->pages }} pages</span>
                                                </div>
                                            @endif
                                            
                                            <!-- Available Copies -->
                                            <div class="flex items-center text-xs font-semibold text-green-600 dark:text-green-400">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ $book->available_copies }} copies available</span>
                                        </div>

                                        <!-- Borrow Request Button (same logic as home) -->
                                        @auth
                                            @if(Auth::user()->status === 'approved')
                                                @php
                                                    $existingLoan = \App\Models\Models\Loan::where('user_id', auth()->id())
                                                        ->where('book_id', $book->id)
                                                        ->whereIn('status',["pending","issued"]) 
                                                        ->first();
                                                @endphp
                                                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
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
                                                                <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white text-xs font-medium rounded-lg shadow-sm hover:shadow-md transform hover:scale-105 transition-all duration-200">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                    </svg>
                                                                    Borrow
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button class="w-full px-3 py-2 bg-gray-300 text-gray-500 text-xs font-medium rounded-lg cursor-not-allowed" disabled>
                                                                Not Available
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            @else
                                                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                                    <div class="text-center">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                            </svg>
                                                            Pending Approval
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
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
