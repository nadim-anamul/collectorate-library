<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">{{ __('ui.user') }} • {{ $user->name }}</h2>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('ui.back_to_users') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('generated_password'))
                <div class="mb-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200"><strong>One-time password:</strong> <code class="px-2 py-1 bg-white/70 dark:bg-gray-700 rounded">{{ session('generated_password') }}</code>. Share it securely with the user. They will be forced to set a new password on next login.</p>
                </div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">{{ __('ui.profile') }}</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ $user->email }}</div>
                        @if($user->phone)
                            <div class="text-sm text-gray-600 dark:text-gray-300">{{ __('ui.phone') }}: {{ $user->phone }}</div>
                        @endif
                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ __('ui.status') }}: <span class="font-semibold">{{ ucfirst($user->status) }}</span></div>
                        @if($user->roles->count())
                            <div class="text-sm text-gray-600 dark:text-gray-300">{{ __('ui.role') }}: {{ $user->roles->pluck('name')->join(', ') }}</div>
                        @endif
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">{{ __('ui.admin_actions') }}</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <form method="POST" action="{{ route('admin.users.revoke', $user) }}" onsubmit="return confirm('Revoke access for this user?')">
                            @csrf
                            <button class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-xl transition duration-200">{{ __('ui.revoke_access') }}</button>
                        </form>

                        <form method="POST" action="{{ route('admin.users.resetPassword', $user) }}" onsubmit="return confirm('Reset password for this user? A one-time password will be generated.')">
                            @csrf
                            <button class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl transition duration-200">{{ __('ui.reset_password') }}</button>
                        </form>
                        @if($user->force_password_reset)
                            <p class="text-xs text-gray-500 dark:text-gray-400">User is flagged to change password on next login.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">User Details</h2>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('ui.back_to_users') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ $user->name }}
                    </h3>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Name</div>
                            <div class="text-base font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Email</div>
                            <div class="text-base font-medium text-gray-900 dark:text-white break-all">{{ $user->email }}</div>
                        </div>
                        @if(!empty($user->phone))
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Phone</div>
                            <div class="text-base font-medium text-gray-900 dark:text-white">{{ $user->phone }}</div>
                        </div>
                        @endif
                        @if(!empty($user->member_type))
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Member Type</div>
                            <div class="text-base font-medium text-gray-900 dark:text-white">{{ $user->member_type }}</div>
                        </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                            <div>
                                @php $status = strtolower($user->status ?? 'pending'); @endphp
                                @if($status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Approved</span>
                                @elseif($status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Rejected</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Pending</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Roles</div>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @forelse($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ $role->name }}</span>
                                @empty
                                    <span class="text-sm text-gray-500 dark:text-gray-400">No role assigned</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Registered At</div>
                            <div class="text-base font-medium text-gray-900 dark:text-white">{{ optional($user->created_at)->format('M d, Y h:i A') }}</div>
                            @if($user->approved_at)
                                <div class="text-sm text-gray-500 dark:text-gray-400">Approved At</div>
                                <div class="text-base font-medium text-gray-900 dark:text-white">{{ optional($user->approved_at)->format('M d, Y h:i A') }}</div>
                            @endif
                            @if($user->approvedBy)
                                <div class="text-sm text-gray-500 dark:text-gray-400">Approved By</div>
                                <div class="text-base font-medium text-gray-900 dark:text-white">{{ $user->approvedBy->name }}</div>
                            @endif
                            @if($user->rejection_reason)
                                <div class="text-sm text-gray-500 dark:text-gray-400">Rejection Reason</div>
                                <div class="text-sm text-red-600 dark:text-red-400">{{ $user->rejection_reason }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-3">
                    <a href="mailto:{{ $user->email }}" class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-lg transition duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m0 0l4-4m-4 4l4 4M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"/></svg>
                        Contact
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-150">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
