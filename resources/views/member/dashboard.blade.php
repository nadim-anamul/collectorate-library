<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 dark:text-white leading-tight">
                    {{ __('ui.welcome_user', ['name' => $user->name]) }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ ucfirst($user->member_type) }} {{ __('ui.member') }} • {{ __('ui.member_since', ['date' => $user->created_at->format('M Y')]) }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                    {{ $stats['current_loans'] }} {{ __('ui.active') }}
                </span>
                @if($stats['overdue_books'] > 0)
                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">
                        {{ $stats['overdue_books'] }} {{ __('ui.overdue') }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        @if($stats['overdue_books'] > 0)
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
                <div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 dark:bg-red-900/20 dark:text-red-200 dark:border-red-800">
                    {{ __('ui.you_have_overdue', ['count' => $stats['overdue_books']]) }}
                </div>
            </div>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-400 bg-opacity-20 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-blue-100">{{ __('ui.current_loans') }}</p>
                            <p class="text-3xl font-bold">{{ $stats['current_loans'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-400 bg-opacity-20 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-purple-100">{{ __('ui.reading_status') }}</p>
                            <p class="text-3xl font-bold">
                                @if($stats['overdue_books'] > 0)
                                    {{ $stats['overdue_books'] }} {{ __('ui.overdue') }}
                                @else
                                    {{ __('ui.up_to_date') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-400 bg-opacity-20 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-green-100">{{ __('ui.total_borrowed') }}</p>
                            <p class="text-3xl font-bold">{{ $stats['total_borrowed'] }}</p>
                        </div>
                    </div>
                </div>

                
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Current Loans -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('ui.current_loans') }}</h3>
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
                                                {{ __('ui.unknown_author') }}
                                            @endif
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center">
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ __('ui.due') }}: {{ \Carbon\Carbon::parse($loan->due_at)->format('M d, Y') }}
                                        </span>
                                        @if(\Carbon\Carbon::parse($loan->due_at) < now())
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ __('ui.overdue') }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    @if($loan->status === 'issued')
                                        <form action="{{ route('loans.requestReturn', $loan) }}" method="POST">@csrf
                                            <button class="px-3 py-2 text-sm bg-yellow-600 hover:bg-yellow-700 text-white rounded">{{ __('ui.request_return') }}</button>
                                        </form>
                                    @elseif($loan->status === 'return_requested')
                                        <span class="px-3 py-2 text-sm rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">{{ __('ui.return_requested') }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('ui.no_current_loans') }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ __('ui.browse_collection_first_book') }}</p>
                                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                                    {{ __('ui.browse_books') }}
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pending Requests -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('ui.pending_requests') }}</h3>
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
                                                {{ __('ui.unknown_author') }}
                                            @endif
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ __('ui.requested') }}: {{ \Carbon\Carbon::parse($loan->requested_at)->format('M d, Y') }}
                                        @if($loan->requested_due_at)
                                            • {{ __('ui.requested_due') }}: {{ \Carbon\Carbon::parse($loan->requested_due_at)->format('M d, Y') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex-shrink-0 flex items-center space-x-3">
                                    <span class="px-3 py-2 text-sm rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">{{ __('ui.pending') }}</span>
                                    <form method="POST" action="{{ route('loans.cancel', $loan) }}" class="inline" onsubmit="return confirm('{{ __('ui.cancel_request_confirm') }}')">
                                        @csrf
                                        <button type="submit" class="px-3 py-2 text-sm rounded bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800 transition-colors duration-200 flex items-center space-x-1 group" title="{{ __('ui.cancel_request') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span class="hidden sm:inline">{{ __('ui.cancel_request') }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('ui.no_pending_requests') }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ __('ui.request_book_details_page') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Reading History -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('ui.recent_reading_history') }}</h3>
                    <a href="{{ route('dashboard.history') }}" class="text-sm px-3 py-1 rounded border dark:border-gray-600">{{ __('ui.view_all') }}</a>
                </div>
                <div class="p-6">
                    @forelse($loanHistory as $loan)
                        <div class="flex items-center space-x-6 py-6 {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-700' : '' }}">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center shadow-sm">
                                    @if($loan->book->cover_path)
                                        <img src="{{ Storage::url($loan->book->cover_path) }}" alt="{{ $loan->book->title_en }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                    @if($loan->book->language && $loan->book->language->code === 'bn' && $loan->book->title_bn)
                                        {{ $loan->book->title_bn }}
                                    @else
                                        {{ $loan->book->title_en }}
                                    @endif
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
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
                                            {{ __('ui.unknown_author') }}
                                        @endif
                                    @endif
                                </p>
                                <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ __('ui.returned') }}: {{ \Carbon\Carbon::parse($loan->returned_at)->format('M d, Y') }}
                                    </span>
                                    @if($loan->book->publication_year)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $loan->book->publication_year }}
                                        </span>
                                    @endif
                                    @if($loan->book->pages)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            {{ $loan->book->pages }} {{ __('ui.pages') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('ui.completed') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">{{ __('ui.no_reading_history_yet') }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">{{ __('ui.completed_books_appear_here') }}</p>
                            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Browse Books
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recommended Books Slider -->
            @if($recommendedBooks->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    {{ __('ui.recommended_for_you') }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('ui.books_might_enjoy') }}</p>
                            </div>
                            <a href="{{ route('home') }}" class="inline-flex items-center text-xs sm:text-sm px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <span class="hidden sm:inline">{{ __('ui.see_all_books') }}</span>
                                <span class="sm:hidden">{{ __('ui.all_books') }}</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Slider Container -->
                    <div class="relative overflow-hidden" x-data="recommendationSlider()" x-init="init()">
                        <div class="p-3 sm:p-6">
                            <!-- Slider Track -->
                            <div class="relative">
                                <div class="flex transition-transform duration-500 ease-in-out" 
                                     :style="`transform: translateX(-${currentSlide * slideWidth}px)`">
                                    @foreach($recommendedBooks as $index => $book)
                                        <div class="flex-shrink-0 mr-3 sm:mr-6 w-48 sm:w-56 md:w-64">
                                            <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 hover:border-purple-200 dark:hover:border-purple-600 hover:-translate-y-1 flex flex-col h-full min-h-[400px] sm:min-h-[480px] md:min-h-[520px]">
                                                <!-- Book Cover - Clickable -->
                                                <a href="{{ route('books.show', $book->id) }}" class="relative aspect-[3/4] bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 overflow-hidden block">
                                                    @if($book->cover_path)
                                                        <img src="{{ Storage::url($book->cover_path) }}" 
                                                             alt="{{ $book->title_en }}" 
                                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center">
                                                            <div class="text-center">
                                                                <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900 dark:to-indigo-900 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-300">
                                                                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                                    </svg>
                                                                </div>
                                                                <p class="text-xs font-medium text-gray-400 dark:text-gray-500">{{ __('ui.no_cover') }}</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Availability Badge -->
                                                    <div class="absolute top-3 right-3">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 shadow-sm">
                                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse"></div>
                                                            {{ __('ui.available') }}
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Recommendation Badge -->
                                                    <div class="absolute top-3 left-3">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 shadow-sm">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                            </svg>
                                                            {{ __('ui.recommended') }}
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Hover Overlay (visual only) -->
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                                                </a>
                                                
                                                <!-- Book Info -->
                                                <div class="p-3 sm:p-4 flex flex-col flex-grow">
                                                    <!-- Title -->
                                                    <h3 class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">
                                                        @if($book->language && $book->language->code === 'bn' && $book->title_bn)
                                                            {{ $book->title_bn }}
                                                        @else
                                                            {{ $book->title_en }}
                                                        @endif
                                                    </h3>
                                                    
                                                    <!-- Author -->
                                                    @if($book->primaryAuthor)
                                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 flex items-center">
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
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
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
                                                    
                                                    <!-- Quick Borrow Button -->
                                                    @auth
                                                        @if(Auth::user()->status === 'approved')
                                                            @php
                                                                $existingLoan = \App\Models\Models\Loan::where('user_id', auth()->id())
                                                                    ->where('book_id', $book->id)
                                                                    ->whereIn('status',["pending","issued"]) 
                                                                    ->first();
                                                            @endphp
                                                            <div class="mt-auto pt-3 border-t border-gray-100 dark:border-gray-700">
                                                                @if($existingLoan)
                                                                    <div class="text-center">
                                                                        <span class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                            </svg>
                                                                            {{ ucfirst($existingLoan->status) }}
                                                                        </span>
                                                                    </div>
                                                                @else
                                                                    @if($book->available_copies > 0)
                                                                        <form action="{{ route('books.request', $book->id) }}" method="POST" class="w-full">
                                                                            @csrf
                                                                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transform hover:scale-105 transition-all duration-200">
                                                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                                </svg>
                                                                                {{ __('ui.quick_borrow') }}
                                                                            </button>
                                                                        </form>
                                                                    @else
                                                                        <form action="{{ route('books.reserve', $book->id) }}" method="POST" class="w-full">
                                                                            @csrf
                                                                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transform hover:scale-105 transition-all duration-200">
                                                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                                </svg>
                                                                                {{ __('ui.reserve') }}
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Slider Indicators -->
                            <div class="flex justify-center mt-6 space-x-2">
                                @for($i = 0; $i < ceil($recommendedBooks->count() / 4); $i++)
                                    <button @click="goToSlide({{ $i }})" 
                                            class="w-2 h-2 rounded-full transition-all duration-200"
                                            :class="currentSlide === {{ $i }} ? 'bg-purple-600 w-6' : 'bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500'">
                                    </button>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function recommendationSlider() {
            return {
                currentSlide: 0,
                slideWidth: 192, // Base width for mobile (w-48)
                totalSlides: 0,
                visibleSlides: 4,

                init() {
                    this.updateSlideWidth();
                    this.updateVisibleSlides();
                    this.totalSlides = Math.ceil({{ $recommendedBooks->count() }} / this.visibleSlides);
                    
                    // Auto-play slider
                    setInterval(() => {
                        if (this.totalSlides > 1) this.nextSlide();
                    }, 5000);
                },

                updateSlideWidth() {
                    if (window.innerWidth < 640) {
                        this.slideWidth = 192; // w-48 + mr-3
                    } else if (window.innerWidth < 768) {
                        this.slideWidth = 224; // w-56 + mr-3
                    } else {
                        this.slideWidth = 256; // w-64 + mr-6
                    }
                },

                updateVisibleSlides() {
                    // Responsive slide count - original desktop behavior preserved
                    if (window.innerWidth < 768) {
                        this.visibleSlides = 1; // Mobile: 1 book
                    } else if (window.innerWidth < 1024) {
                        this.visibleSlides = 2; // Tablet: 2 books
                    } else if (window.innerWidth < 1280) {
                        this.visibleSlides = 3; // Small desktop: 3 books
                    } else {
                        this.visibleSlides = 4; // Desktop: 4 books (original)
                    }
                    
                    this.updateSlideWidth();
                    this.totalSlides = Math.ceil({{ $recommendedBooks->count() }} / this.visibleSlides);
                    
                    // Ensure current slide is within bounds
                    if (this.currentSlide >= this.totalSlides) {
                        this.currentSlide = Math.max(0, this.totalSlides - 1);
                    }
                },

                nextSlide() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                },

                previousSlide() {
                    this.currentSlide = this.currentSlide === 0 ? this.totalSlides - 1 : this.currentSlide - 1;
                },

                goToSlide(index) {
                    this.currentSlide = index;
                }
            }
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            // Trigger re-initialization if needed
            const slider = Alpine.$data(document.querySelector('[x-data="recommendationSlider()"]'));
            if (slider) {
                slider.updateVisibleSlides();
            }
        });
    </script>
</x-app-layout>
