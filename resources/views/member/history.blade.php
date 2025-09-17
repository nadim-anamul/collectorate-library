<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Reading History</h2>
            <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded border border-gray-200 dark:border-gray-600 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-100">Back to Dashboard</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" action="{{ route('dashboard.history') }}" class="mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-3">
                            <div class="md:col-span-2">
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Search</label>
                                <input type="text" name="q" value="{{ $q }}" placeholder="Book title or ISBN" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                                    <option value="">All</option>
                                    @foreach(['issued','returned','declined'] as $s)
                                        <option value="{{ $s }}" @selected(($status ?? '')===$s)>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Issued From</label>
                                <input type="date" name="issued_from" value="{{ $issuedFrom }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Issued To</label>
                                <input type="date" name="issued_to" value="{{ $issuedTo }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Due From</label>
                                <input type="date" name="due_from" value="{{ $dueFrom }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 dark:text-gray-300">Due To</label>
                                <input type="date" name="due_to" value="{{ $dueTo }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                            </div>
                        </div>
                        <div class="mt-3 flex items-center gap-2">
                            <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">Apply</button>
                            <a href="{{ route('dashboard.history') }}" class="px-4 py-2 rounded border dark:border-gray-600">Clear</a>
                        </div>
                    </form>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr class="text-left text-gray-700 dark:text-gray-200">
                                    <th class="py-2 px-3">Book</th>
                                    <th class="py-2 px-3">Author</th>
                                    <th class="py-2 px-3">Requested</th>
                                    <th class="py-2 px-3">Issued</th>
                                    <th class="py-2 px-3">Due</th>
                                    <th class="py-2 px-3">Returned</th>
                                    <th class="py-2 px-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($history as $loan)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="py-2 px-3">
                                            @if($loan->book->language && $loan->book->language->code === 'bn' && $loan->book->title_bn)
                                                {{ $loan->book->title_bn }}
                                            @else
                                                {{ $loan->book->title_en }}
                                            @endif
                                        </td>
                                        <td class="py-2 px-3">
                                            @if($loan->book->primaryAuthor)
                                                @if($loan->book->language && $loan->book->language->code === 'bn' && $loan->book->primaryAuthor->name_bn)
                                                    {{ $loan->book->primaryAuthor->name_bn }}
                                                @else
                                                    {{ $loan->book->primaryAuthor->name_en }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-2 px-3">{{ $loan->requested_at ?: '-' }}</td>
                                        <td class="py-2 px-3">{{ $loan->issued_at ?: '-' }}</td>
                                        <td class="py-2 px-3">{{ $loan->due_at ?: '-' }}</td>
                                        <td class="py-2 px-3">{{ $loan->returned_at ?: '-' }}</td>
                                        <td class="py-2 px-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                                                {{ $loan->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                {{ $loan->status === 'issued' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                {{ $loan->status === 'returned' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                {{ $loan->status === 'declined' ? 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                                            ">{{ ucfirst($loan->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-gray-600 dark:text-gray-400">No history yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $history->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
