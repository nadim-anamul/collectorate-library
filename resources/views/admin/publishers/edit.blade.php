<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Publisher</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.publishers.update', $publisher) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm">Name (EN)</label>
                        <input name="name_en" value="{{ old('name_en', $publisher->name_en) }}" class="mt-1 w-full border p-2 rounded" required />
                    </div>
                    <div>
                        <label class="block text-sm">Name (BN)</label>
                        <input name="name_bn" value="{{ old('name_bn', $publisher->name_bn) }}" class="mt-1 w-full border p-2 rounded" />
                    </div>
                    <div>
                        <label class="block text-sm">Website</label>
                        <input name="website" value="{{ old('website', $publisher->website) }}" class="mt-1 w-full border p-2 rounded" />
                    </div>
                    <div class="flex justify-end">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


