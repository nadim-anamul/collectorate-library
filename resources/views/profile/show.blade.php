<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">{{ __('ui.my_profile') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">{{ session('status') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">{{ __('ui.name') }}</div>
                        <div class="text-gray-900 dark:text-white font-medium">{{ $user->name }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">{{ __('ui.email_address') }}</div>
                        <div class="text-gray-900 dark:text-white font-medium">{{ $user->email }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">{{ __('ui.phone') }}</div>
                        <div class="text-gray-900 dark:text-white font-medium">{{ $user->phone ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">{{ __('ui.job_post') }}</div>
                        <div class="text-gray-900 dark:text-white font-medium">{{ $user->job_post ?: '—' }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-gray-500 dark:text-gray-400">{{ __('ui.address') }}</div>
                        <div class="text-gray-900 dark:text-white font-medium">{{ $user->address ?: '—' }}</div>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">{{ __('ui.edit_profile') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


