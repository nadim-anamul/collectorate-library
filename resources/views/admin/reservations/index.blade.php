<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Reservations</h2>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <form method="GET" action="{{ route('admin.reservations.index') }}" class="flex items-center gap-3">
                        <label class="text-sm text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" class="border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                            <option value="">All</option>
                            @foreach(['active','fulfilled','cancelled'] as $s)
                                <option value="{{ $s }}" @selected(($status ?? '')===$s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        <button class="px-3 py-2 bg-indigo-600 text-white rounded">Apply</button>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr class="text-left text-gray-700 dark:text-gray-200">
                                <th class="py-2 px-3">User</th>
                                <th class="py-2 px-3">Book</th>
                                <th class="py-2 px-3">Queued</th>
                                <th class="py-2 px-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($reservations as $r)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="py-2 px-3">
                                        <div class="font-medium">{{ $r->user->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $r->user->email }}</div>
                                    </td>
                                    <td class="py-2 px-3">{{ $r->book->title_en ?: $r->book->title_bn }}</td>
                                    <td class="py-2 px-3">{{ \Carbon\Carbon::parse($r->queued_at)->format('M d, Y H:i') }}</td>
                                    <td class="py-2 px-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                                            {{ $r->status === 'active' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                            {{ $r->status === 'fulfilled' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                            {{ $r->status === 'cancelled' ? 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                                        ">{{ ucfirst($r->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-gray-500 dark:text-gray-400">No reservations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $reservations->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>


