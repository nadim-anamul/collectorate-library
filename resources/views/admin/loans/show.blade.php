<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Loan #{{ $loan->id }}</h2>
            <a href="{{ route('admin.loans.index') }}" class="px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded">Back</a>
        </div>
    </x-slot>
    <div class="py-6" x-data="{ showDeclineModal: false }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="p-6 space-y-4">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Borrower</div>
                        <div class="font-medium">{{ $loan->user->name }} <span class="text-xs text-gray-500">{{ $loan->user->email }}</span></div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Book</div>
                        <div class="font-medium">{{ $loan->book->title_en ?? $loan->book->title_bn }}</div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Requested</div>
                            <div class="font-medium">{{ $loan->requested_at ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Issued</div>
                            <div class="font-medium">{{ $loan->issued_at ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Due</div>
                            <div class="font-medium">{{ $loan->due_at ?: '-' }}</div>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                        <div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                                {{ $loan->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                {{ $loan->status === 'issued' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                {{ $loan->status === 'return_requested' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : '' }}
                                {{ $loan->status === 'returned' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                {{ $loan->status === 'declined' ? 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                            ">{{ ucfirst($loan->status) }}</span>
                        </div>
                        @if($loan->decline_reason)
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                <span class="font-semibold">Decline reason:</span> {{ $loan->decline_reason }}
                            </div>
                        @endif
                    </div>

                    @if($loan->status === 'pending')
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="font-semibold mb-2 text-gray-800 dark:text-gray-200">Approve</h3>
                                    <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="block text-sm text-gray-700 dark:text-gray-300">Issued At</label>
                                            <input type="date" name="issued_at" value="{{ old('issued_at', now()->toDateString()) }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-700 dark:text-gray-300">Due At</label>
                                            <input type="date" name="due_at" value="{{ old('due_at') }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                                        </div>
                                        <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Approve & Issue</button>
                                    </form>
                                </div>
                                <div>
                                    <h3 class="font-semibold mb-2 text-gray-800 dark:text-gray-200">Decline</h3>
                                    <button @click="showDeclineModal = true" class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded">
                                        Decline Loan
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif($loan->status === 'issued')
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-center">
                                <form method="POST" action="{{ route('admin.loans.return', $loan) }}">
                                    @csrf
                                    <button class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
                                        Mark as Returned
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
                                            Confirm Return
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
    <div x-show="showDeclineModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @click.away="showDeclineModal = false">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDeclineModal = false"></div>
            
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
                                    Decline Loan Request
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Please provide a reason for declining this loan request for "{{ $loan->book->title_en ?? $loan->book->title_bn }}".
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <label for="decline_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Decline Reason *</label>
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
                            Decline Loan
                        </button>
                        <button type="button" @click="showDeclineModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


