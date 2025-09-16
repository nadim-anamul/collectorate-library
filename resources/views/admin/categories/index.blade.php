<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Categories</h2>
            <a href="{{ route('admin.categories.create') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Add Category</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full text-sm">
                        <thead><tr class="text-left"><th class="py-2">Name (EN)</th><th class="py-2">Name (BN)</th><th class="py-2">Slug</th><th class="py-2">Actions</th></tr></thead>
                        <tbody>
                        @foreach($categories as $category)
                            <tr class="border-t">
                                <td class="py-2">{{ $category->name_en }}</td>
                                <td class="py-2">{{ $category->name_bn }}</td>
                                <td class="py-2">{{ $category->slug }}</td>
                                <td class="py-2 space-x-2">
                                    <a href="{{ route('admin.categories.edit',$category) }}" class="text-indigo-600">Edit</a>
                                    <form action="{{ route('admin.categories.destroy',$category) }}" method="POST" class="inline">@csrf @method('DELETE')<button class="text-red-600" onclick="return confirm('Delete?')">Delete</button></form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $categories->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
