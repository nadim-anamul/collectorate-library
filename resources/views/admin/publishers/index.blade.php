<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Publishers</h2>
            <a href="{{ route('admin.publishers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Add Publisher</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-sm text-gray-600">
                            <th class="p-2">Name (EN)</th>
                            <th class="p-2">Website</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($publishers as $publisher)
                        <tr class="border-t">
                            <td class="p-2">{{ $publisher->name_en }}</td>
                            <td class="p-2">{{ $publisher->website }}</td>
                            <td class="p-2 space-x-2">
                                <a href="{{ route('admin.publishers.edit', $publisher) }}" class="text-blue-600">Edit</a>
                                <form action="{{ route('admin.publishers.destroy', $publisher) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600" onclick="return confirm('Delete publisher?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $publishers->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>


