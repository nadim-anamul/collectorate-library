<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Language</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.languages.update', $language) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm">Code</label>
                        <input name="code" value="{{ old('code', $language->code) }}" class="mt-1 w-full border p-2 rounded" required />
                    </div>
                    <div>
                        <label class="block text-sm">Name</label>
                        <input name="name" value="{{ old('name', $language->name) }}" class="mt-1 w-full border p-2 rounded" required />
                    </div>
                    <div class="flex justify-end">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
