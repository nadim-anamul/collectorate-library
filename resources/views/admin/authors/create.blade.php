<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Author</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.authors.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm">Name (EN)</label>
                        <input name="name_en" class="mt-1 w-full border p-2 rounded" required />
                    </div>
                    <div>
                        <label class="block text-sm">Name (BN)</label>
                        <input name="name_bn" class="mt-1 w-full border p-2 rounded" />
                    </div>
                    <div>
                        <label class="block text-sm">Bio</label>
                        <textarea name="bio" class="mt-1 w-full border p-2 rounded"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


