<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">All Notifications</h2>
            <form method="POST" action="{{ route('notifications.read') }}">@csrf
                <button class="text-sm px-3 py-1 rounded border dark:border-gray-600">Mark all read</button>
            </form>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($notifications as $n)
                        <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <div class="flex items-start">
                                <div class="flex-1">
                                    <a href="{{ $n->data['url'] ?? '#' }}" class="block">
                                        <p class="text-sm {{ $n->read_at ? 'text-gray-500 dark:text-gray-400' : 'text-gray-800 dark:text-gray-100 font-medium' }}">
                                            {{ $n->data['message'] ?? 'Notification' }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $n->created_at->toDateTimeString() }}</p>
                                    </a>
                                </div>
                                @if(!$n->read_at)
                                    <form method="POST" action="{{ route('notifications.read') }}" class="ml-3">@csrf
                                        <input type="hidden" name="id" value="{{ $n->id }}" />
                                        <button class="text-xs px-2 py-1 rounded border dark:border-gray-600">Mark read</button>
                                    </form>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="p-8 text-center text-gray-500 dark:text-gray-400">No notifications</li>
                    @endforelse
                </ul>
            </div>
            <div class="mt-4">{{ $notifications->links() }}</div>
        </div>
    </div>
</x-app-layout>


