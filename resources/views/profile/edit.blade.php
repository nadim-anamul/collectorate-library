<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Edit Profile</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:border-gray-600" required>
                        @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:border-gray-600" required>
                        @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:border-gray-600">
                        @error('phone')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Job Post</label>
                        <input type="text" name="job_post" value="{{ old('job_post', $user->job_post) }}" class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:border-gray-600">
                        @error('job_post')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Address</label>
                        <textarea name="address" rows="3" class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:border-gray-600">{{ old('address', $user->address) }}</textarea>
                        @error('address')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-4 border-t dark:border-gray-700">
                        <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Change Password (optional)</label>
                        <input type="password" name="password" class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:border-gray-600 mb-2" placeholder="New password">
                        <input type="password" name="password_confirmation" class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:border-gray-600" placeholder="Confirm new password">
                        @error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('profile.show') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">Cancel</a>
                        <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


