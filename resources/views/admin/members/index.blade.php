<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Members</h2>
            <a href="{{ route('admin.members.create') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Add Member</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full text-sm">
                        <thead><tr class="text-left"><th class="py-2">Member ID</th><th class="py-2">Name</th><th class="py-2">Type</th><th class="py-2">Active</th><th class="py-2">Actions</th></tr></thead>
                        <tbody>
                        @foreach($members as $member)
                            <tr class="border-t">
                                <td class="py-2">{{ $member->member_id }}</td>
                                <td class="py-2">{{ $member->name }}</td>
                                <td class="py-2">{{ $member->type }}</td>
                                <td class="py-2">{{ $member->active ? 'Yes' : 'No' }}</td>
                                <td class="py-2 space-x-2">
                                    <a href="{{ route('admin.members.edit',$member) }}" class="text-indigo-600">Edit</a>
                                    <a href="{{ route('admin.members.card',$member) }}" class="text-green-600" target="_blank">Card</a>
                                    <form action="{{ route('admin.members.destroy',$member) }}" method="POST" class="inline">@csrf @method('DELETE')<button class="text-red-600" onclick="return confirm('Delete?')">Delete</button></form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $members->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
