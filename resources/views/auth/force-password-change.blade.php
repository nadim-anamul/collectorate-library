<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Set a New Password</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Security Check</h3>
                </div>
                <form method="POST" action="{{ route('password.change.store') }}" class="p-6 space-y-6">
                    @csrf
                    <p class="text-sm text-gray-600 dark:text-gray-300">You logged in with a one-time password. Please set a new password now.</p>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">New Password</label>
                        <input type="password" name="password" required class="mt-1 w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="mt-1 w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" />
                    </div>
                    <div class="flex justify-end">
                        <button class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl transition duration-200">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


