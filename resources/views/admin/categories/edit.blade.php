<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Category</h2></x-slot>
    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.categories.update',$category) }}" method="POST" class="space-y-4">@csrf @method('PUT')
                        <div><label class="block text-sm">Name (EN)</label><input name="name_en" value="{{ old('name_en',$category->name_en) }}" class="mt-1 w-full border rounded p-2" required /></div>
                        <div><label class="block text-sm">Name (BN)</label><input name="name_bn" value="{{ old('name_bn',$category->name_bn) }}" class="mt-1 w-full border rounded p-2" required /></div>
                        <div><label class="block text-sm">Slug</label><input name="slug" value="{{ old('slug',$category->slug) }}" class="mt-1 w-full border rounded p-2" required /></div>
                        <div class="flex justify-end"><button class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
