<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Issue New Book</h2>
            <a href="{{ route('admin.loans.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Loans
            </a>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if($errors && $errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div class="ml-3">
                            <h4 class="font-medium text-red-800 dark:text-red-200 mb-2">Please fix the following errors:</h4>
                            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"></path></svg>
                        Loan Information
                    </h3>
                </div>
                <form action="{{ route('admin.loans.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Borrower *</label>
                            <select name="user_id" required class="mt-1 w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" @selected(old('user_id')==$u->id)>{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Only approved users are listed.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Book *</label>
                            <select name="book_id" required class="mt-1 w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                                @foreach($books as $b)
                                    <option value="{{ $b->id }}" @selected(old('book_id')==$b->id) {{ $b->available_copies < 1 ? 'disabled' : '' }}>
                                        {{ $b->title_en }} ({{ $b->available_copies }} available)
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Unavailable books are disabled.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Issued Date *</label>
                            <input type="date" name="issued_at" value="{{ old('issued_at', now()->toDateString()) }}" required class="mt-1 w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Due Date *</label>
                            <input type="date" name="due_at" value="{{ old('due_at', now()->addDays(14)->toDateString()) }}" required class="mt-1 w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white" />
                        </div>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                        <p class="text-sm text-blue-800 dark:text-blue-200">The due date defaults to 14 days from the issue date.</p>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Cancel
                        </a>
                        <button class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl transition duration-200 transform hover:scale-105 shadow-lg font-medium">Issue Book</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
