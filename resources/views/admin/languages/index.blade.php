<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Languages</h2>
            <a href="{{ route('admin.languages.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Add Language</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-sm text-gray-600">
                            <th class="p-2">Code</th>
                            <th class="p-2">Name</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($languages as $language)
                        <tr class="border-t">
                            <td class="p-2">{{ $language->code }}</td>
                            <td class="p-2">{{ $language->name }}</td>
                            <td class="p-2 space-x-2">
                                <a href="{{ route('admin.languages.edit', $language) }}" class="text-blue-600">Edit</a>
                                <form action="{{ route('admin.languages.destroy', $language) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600" onclick="return confirm('Delete language?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $languages->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>


