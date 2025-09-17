<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Publishers</h2>
            <a href="{{ route('admin.publishers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">Add Publisher</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        Publisher Management
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr class="text-sm text-gray-600 dark:text-gray-300">
                                <th class="px-6 py-3 font-medium">Name (EN)</th>
                                <th class="px-6 py-3 font-medium">Website</th>
                                <th class="px-6 py-3 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($publishers as $publisher)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-6 py-4 text-gray-900 dark:text-white font-medium">{{ $publisher->name_en }}</td>
                                    <td class="px-6 py-4">
                                        @if($publisher->website)
                                            <a href="{{ $publisher->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline break-all">{{ $publisher->website }}</a>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.publishers.edit', $publisher) }}" class="inline-flex items-center px-3 py-1.5 text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-md transition duration-150">Edit</a>
                                            <form action="{{ route('admin.publishers.destroy', $publisher) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="inline-flex items-center px-3 py-1.5 text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition duration-150" onclick="return confirm('Delete publisher?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $publishers->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>


