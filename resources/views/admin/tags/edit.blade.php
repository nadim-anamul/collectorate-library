<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Tag</h2></x-slot>
    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.tags.update',$tag) }}" method="POST" class="space-y-4">@csrf @method('PUT')
                        <div><label class="block text-sm">Name</label><input name="name" value="{{ old('name',$tag->name) }}" class="mt-1 w-full border rounded p-2" required /></div>
                        <div><label class="block text-sm">Slug</label><input name="slug" value="{{ old('slug',$tag->slug) }}" class="mt-1 w-full border rounded p-2" required /></div>
                        <div class="flex justify-end"><button class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
