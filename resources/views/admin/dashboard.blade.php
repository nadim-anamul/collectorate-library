<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Admin Dashboard</h2>
            <div class="text-sm text-gray-600 dark:text-gray-300">Quick overview of your library</div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Books</div>
                    <div class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['books'] ?? 0 }}</div>
                </div>
                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Available</div>
                    <div class="mt-2 text-2xl font-bold text-green-600">{{ $stats['available_books'] ?? 0 }}</div>
                </div>
                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Categories</div>
                    <div class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['categories'] ?? 0 }}</div>
                </div>
                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Members</div>
                    <div class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['members'] ?? 0 }}</div>
                </div>
                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Active Loans</div>
                    <div class="mt-2 text-2xl font-bold text-orange-600">{{ $stats['loans_active'] ?? 0 }}</div>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Filters -->
                <div class="lg:w-1/4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Filters</h3>
                        <form method="GET" class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-2">Category</label>
                                <select name="category" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">All</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ ($filters['category'] ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-2">Language</label>
                                <select name="language" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">All</option>
                                    @foreach($languages as $language)
                                        <option value="{{ $language }}" {{ ($filters['language'] ?? '') == $language ? 'selected' : '' }}>{{ $language }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-300 mb-2">Year</label>
                                <select name="year" class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">All</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ ($filters['year'] ?? '') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex space-x-2">
                                <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Apply</button>
                                <a href="{{ route('admin.home') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-center">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Main Grid + Activity -->
                <div class="lg:w-3/4 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <div class="p-4 border-b dark:border-gray-700 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Books</h3>
                            <a href="{{ route('admin.books.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Add Book</a>
                        </div>
                        <div class="p-4">
                            @if(isset($books) && $books->count())
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($books as $book)
                                        <div class="border dark:border-gray-700 rounded overflow-hidden">
                                            <div class="aspect-[3/4] bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                @if($book->cover_path)
                                                    <img src="{{ Storage::url($book->cover_path) }}" alt="{{ $book->title_en }}" class="w-full h-full object-cover">
                                                @else
                                                    <span class="text-gray-400">No Cover</span>
                                                @endif
                                            </div>
                                            <div class="p-3">
                                                <div class="font-semibold text-gray-800 dark:text-white line-clamp-2">{{ $book->title_en }}</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $book->author_en }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $book->publication_year }} • {{ $book->available_copies }} available</div>
                                                <div class="mt-3 flex justify-between">
                                                    <a href="{{ route('admin.books.edit', $book) }}" class="text-blue-600 hover:underline">Edit</a>
                                                    <a href="{{ route('admin.books.index') }}" class="text-gray-600 hover:underline">Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    {{ $books->links() }}
                                </div>
                            @else
                                <div class="text-gray-600 dark:text-gray-400">No books found.</div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <div class="p-4 border-b dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Activity</h3>
                        </div>
                        <div class="p-4 divide-y dark:divide-gray-700">
                            @forelse(($activities ?? []) as $activity)
                                <div class="py-3 text-sm text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">{{ $activity->user_name ?? 'System' }}</span>
                                    <span class="text-gray-500">{{ $activity->action ?? $activity->description ?? '' }}</span>
                                    <span class="text-gray-400">— {{ $activity->created_at->diffForHumans() }}</span>
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
