<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Authors</h2>
            <a href="{{ route('admin.authors.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Add Author</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-sm text-gray-600">
                            <th class="p-2">Name (EN)</th>
                            <th class="p-2">Name (BN)</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($authors as $author)
                        <tr class="border-t">
                            <td class="p-2">{{ $author->name_en }}</td>
                            <td class="p-2">{{ $author->name_bn }}</td>
                            <td class="p-2 space-x-2">
                                <a href="{{ route('admin.authors.edit', $author) }}" class="text-blue-600">Edit</a>
                                <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600" onclick="return confirm('Delete author?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $authors->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>


