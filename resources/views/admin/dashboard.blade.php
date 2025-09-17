<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Admin Dashboard</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm rounded border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700">Users</a>
                <a href="{{ route('admin.loans.index') }}" class="px-3 py-2 text-sm rounded border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700">Loans</a>
                <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 text-sm rounded border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700">Reports</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stat cards: gradient with icons -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="p-4 rounded-xl shadow-sm text-white bg-gradient-to-r from-blue-500 to-indigo-600">
                    <div class="flex items-center">
                        <div class="p-2 bg-white/10 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs opacity-80">Total Books</div>
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
                            <div class="text-xs opacity-80">Available</div>
                            <div class="text-2xl font-bold">{{ $stats['available_books'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.loans.index') }}" class="block p-4 rounded-xl shadow-sm text-white bg-gradient-to-r from-purple-500 to-violet-600 hover:from-purple-600 hover:to-violet-700 transition-all duration-200 transform hover:scale-105 cursor-pointer">
                    <div class="flex items-center">
                        <div class="p-2 bg-white/10 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs opacity-80">Pending Loans</div>
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
                            <div class="text-xs opacity-80">Users</div>
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
                            <div class="text-xs opacity-80">Active Loans</div>
                            <div class="text-2xl font-bold">{{ $stats['loans_active'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar filters styled like home -->
                <div class="lg:w-1/4">
                    <div class="rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold mb-3">
                            <svg class="w-4 h-4 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M5.25 8.25h13.5m-12 3.75h10.5m-9 3.75h7.5M9 19.5h6"/></svg>
                            <span>Filters</span>
                        </div>
                        <form method="GET" class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Search</label>
                                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Title/Author/ISBN" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Category</label>
                                <select name="category" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                                    <option value="">All</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ ($filters['category'] ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Language</label>
                                <select name="language" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                                    <option value="">All</option>
                                    @foreach($languages as $code)
                                        <option value="{{ $code }}" {{ ($filters['language'] ?? '') == $code ? 'selected' : '' }}>{{ strtoupper($code) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Year</label>
                                <select name="year" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                                    <option value="">All</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ ($filters['year'] ?? '') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">Apply</button>
                                <a href="{{ route('admin.home') }}" class="px-4 py-2 rounded border dark:border-gray-600">Clear</a>
                            </div>
                        </form>
                        <div class="mt-6">
                            <div class="text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Quick Links</div>
                            <div class="space-y-2">
                                <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 rounded bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <span class="text-sm">Manage Users</span>
                                </a>
                                <a href="{{ route('admin.loans.index') }}" class="flex items-center px-3 py-2 rounded bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <span class="text-sm">Manage Loans</span>
                                </a>
                                <a href="{{ route('admin.reports.index') }}" class="flex items-center px-3 py-2 rounded bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <span class="text-sm">Reports</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Grid + Activity -->
                <div class="lg:w-3/4 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                        <div class="p-4 border-b dark:border-gray-700 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Books</h3>
                            <a href="{{ route('admin.books.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Add Book</a>
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
                                                            <p class="text-xs font-medium text-gray-400 dark:text-gray-500">No Cover</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="absolute top-2 right-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $book->available_copies > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                        {{ $book->available_copies > 0 ? 'Available' : 'Unavailable' }}
                                                    </span>
                                                </div>
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center">
                                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                        <a href="{{ route('admin.books.edit', $book) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">Edit</a>
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
                                                        {{ $book->author_en ?? $book->author_bn ?? 'Unknown Author' }}
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $book->publication_year }} • {{ $book->available_copies }}/{{ $book->total_copies }} copies</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">{{ $books->links() }}</div>
                            @else
                                <div class="text-gray-600 dark:text-gray-400">No books found.</div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                        <div class="p-4 border-b dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Activity</h3>
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
                                <div class="py-6 text-gray-600 dark:text-gray-400">No recent activity.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
