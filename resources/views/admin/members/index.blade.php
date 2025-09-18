<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Members</h2>
            <a href="{{ route('admin.members.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200 text-center">Add Member</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-white">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr class="text-left text-gray-600 dark:text-gray-300">
                                    <th class="px-3 py-3 font-medium">Member ID</th>
                                    <th class="px-3 py-3 font-medium">Name</th>
                                    <th class="px-3 py-3 font-medium hidden sm:table-cell">Type</th>
                                    <th class="px-3 py-3 font-medium hidden md:table-cell">Active</th>
                                    <th class="px-3 py-3 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($members as $member)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-3 py-3 whitespace-nowrap">{{ $member->member_id }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <div class="text-gray-900 dark:text-white font-medium">{{ $member->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 sm:hidden">Type: {{ ucfirst($member->type) }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 md:hidden">Active: {{ $member->active ? 'Yes' : 'No' }}</div>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap hidden sm:table-cell">{{ ucfirst($member->type) }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap hidden md:table-cell">{{ $member->active ? 'Yes' : 'No' }}</td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <a href="{{ route('admin.members.edit',$member) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 text-xs sm:text-sm">Edit</a>
                                            <a href="{{ route('admin.members.card',$member) }}" target="_blank" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 text-xs sm:text-sm">Card</a>
                                            <form action="{{ route('admin.members.destroy',$member) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs sm:text-sm" onclick="return confirm('Delete?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $members->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
