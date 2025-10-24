<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">{{ __('ui.loan_details') }} • #{{ $loan->id }}</h2>
            <a href="{{ route('admin.loans.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('ui.back_to_loans') }}
            </a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Borrower Card -->
                <div class="lg:col-span-1 bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ __('ui.borrower') }}
                        </h3>
                    </div>
                    <div class="p-6 space-y-2">
                        <div class="text-gray-900 dark:text-white font-semibold">{{ $loan->user->name }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ $loan->user->email }}</div>
                        @if($loan->user->phone)
                            <div class="text-sm text-gray-600 dark:text-gray-300 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.129a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.492 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $loan->user->phone }}
                            </div>
                        @endif
                        @if($loan->user->address)
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($loan->user->address, 120) }}</div>
                        @endif
                    </div>
                </div>

                <!-- Book Card -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>
                            {{ __('ui.book') }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="font-semibold text-gray-900 dark:text-white text-lg mb-1">{{ $loan->book->title_en ?? $loan->book->title_bn }}</div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('ui.requested') }}</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $loan->requested_at ?: '-' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('ui.issued') }}</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $loan->issued_at ?: '-' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('ui.due') }}</div>
                                <div class="text-sm font-medium {{ $loan->due_at && !$loan->returned_at && \Carbon\Carbon::parse($loan->due_at) < now() ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">{{ $loan->due_at ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="mt-4">
                            @php
                                $statusConfig = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'issued' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                    'return_requested' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                    'returned' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'declined' => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusConfig[$loan->status] ?? $statusConfig['pending'] }}">{{ ucfirst(str_replace('_',' ',$loan->status)) }}</span>
                            @if($loan->decline_reason)
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-300"><span class="font-semibold">{{ __('ui.decline_reason') }}:</span> {{ $loan->decline_reason }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

                    @if($loan->status === 'pending')
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Approve Card -->
                                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
                                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                                        <h3 class="text-lg font-semibold text-white flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ __('ui.approve_issue') }}
                                        </h3>
                                    </div>
                                    <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" class="p-6 space-y-4">
                                        @csrf
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Issued Date *</label>
                                                <input type="date" name="issued_at" value="{{ old('issued_at', now()->toDateString()) }}" class="mt-1 w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Due Date *</label>
                                                <input type="date" name="due_at" value="{{ old('due_at', now()->addDays(14)->toDateString()) }}" class="mt-1 w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                                            </div>
                                        </div>
                                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                                            <div class="flex items-start space-x-3">
                                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                                <p class="text-sm text-blue-700 dark:text-blue-300">Due date defaults to 14 days from issue. Adjust if needed.</p>
                                            </div>
                                        </div>
                                        <div class="flex justify-end">
                                            <button class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition duration-200 transform hover:scale-105 shadow-lg font-medium">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ __('ui.approve_issue') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Decline Card -->
                                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
                                    <div class="bg-gradient-to-r from-red-600 to-pink-600 px-6 py-4">
                                        <h3 class="text-lg font-semibold text-white flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                            {{ __('ui.decline_loan') }}
                                        </h3>
                                    </div>
                                    <div class="p-6">
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Open a confirmation form to provide a decline reason.</p>
                                        <button onclick="openDeclineModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-xl transition duration-200 transform hover:scale-105 shadow-lg font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                            {{ __('ui.decline_loan') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($loan->status === 'issued')
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-center">
                                <form method="POST" action="{{ route('admin.loans.return', $loan) }}">
                                    @csrf
                                    <button class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold shadow">
                                        {{ __('ui.mark_returned') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($loan->status === 'return_requested')
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-semibold text-orange-800 dark:text-orange-200">Return Requested</h3>
                                        <p class="text-sm text-orange-600 dark:text-orange-300">The borrower has requested to return this book.</p>
                                    </div>
                                    <form method="POST" action="{{ route('admin.loans.return', $loan) }}">
                                        @csrf
                                        <button class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium">
                                            {{ __('ui.confirm_return') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Decline Modal -->
    <div id="declineModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeclineModal()"></div>
            
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('admin.loans.decline', $loan) }}">
                    @csrf
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('ui.decline_loan_request') }}
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Please provide a reason for declining this loan request for "{{ $loan->book->title_en ?? $loan->book->title_bn }}".
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <label for="decline_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('ui.decline_reason') }} *</label>
                                    <textarea 
                                        id="decline_reason" 
                                        name="decline_reason" 
                                        rows="4" 
                                        required
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                                        placeholder="Enter the reason for declining this loan request..."
                                    >{{ old('decline_reason') }}</textarea>
                                    @error('decline_reason')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('ui.decline_loan') }}
                        </button>
                        <button type="button" onclick="closeDeclineModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ __('ui.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeclineModal() {
            document.getElementById('declineModal').classList.remove('hidden');
        }

        function closeDeclineModal() {
            document.getElementById('declineModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('declineModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeclineModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeclineModal();
            }
        });
    </script>
</x-app-layout>


