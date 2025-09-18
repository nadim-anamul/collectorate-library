<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-2xl text-gray-800 dark:text-white leading-tight">Loan Management</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Manage book loans and returns</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.loans.create') }}" class="group relative px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Issue New Book</span>
                </div>
            </a>
        </div>
    </x-slot>
    
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Loans</p>
                            <p class="text-3xl font-bold">{{ $loans->total() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl p-6 text-white shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Pending</p>
                            <p class="text-3xl font-bold">{{ $loans->where('status', 'pending')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl p-6 text-white shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Active</p>
                            <p class="text-3xl font-bold">{{ $loans->where('status', 'issued')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-red-500 to-pink-500 rounded-2xl p-6 text-white shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm font-medium">Overdue</p>
                            <p class="text-3xl font-bold">{{ $loans->filter(function($loan) { return $loan->due_at && !$loan->returned_at && \Carbon\Carbon::parse($loan->due_at) < now(); })->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:flex lg:gap-8">
                <aside class="lg:w-1/4 w-full mb-8 lg:mb-0">
                    <!-- Modern Filter Sidebar -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4.5h18M5.25 8.25h13.5m-12 3.75h10.5m-9 3.75h7.5M9 19.5h6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Filters & Search</h3>
                                    <p class="text-purple-100 text-sm">Find specific loans</p>
                                </div>
                            </div>
                        </div>
                        
                        <form method="GET" action="{{ route('admin.loans.index') }}" class="p-6 space-y-6">
                            <!-- Search Section -->
                            <div class="space-y-3">
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold">
                                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Search Loans
                                </label>
                                <div class="relative">
                                    <input id="loan-search" type="text" name="q" value="{{ $search }}" placeholder="User, email, book title, ISBN" class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200" />
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Filters -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                        </svg>
                                        Quick Filters
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="overdue" value="1" {{ ($overdueOnly ?? false) ? 'checked' : '' }} class="sr-only peer">
                                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
                                        <span class="ml-3 text-sm font-medium text-red-600 dark:text-red-400">Overdue only</span>
                                    </label>
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                        <select name="status" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200">
                                            <option value="">All Statuses</option>
                                            @foreach(['pending','issued','return_requested','returned','declined'] as $s)
                                                <option value="{{ $s }}" @selected(($status ?? '')===$s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">User</label>
                                        <select name="user_id" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200">
                                            <option value="">All Users</option>
                                            @foreach($users as $u)
                                                <option value="{{ $u->id }}" @selected(($userId ?? '')==$u->id)>{{ $u->name }} ({{ $u->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Date Range Filters -->
                            <div class="space-y-4">
                                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Date Range
                                </label>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Issued From</label>
                                        <input type="date" name="issued_from" value="{{ $issuedFrom }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Issued To</label>
                                        <input type="date" name="issued_to" value="{{ $issuedTo }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Due From</label>
                                        <input type="date" name="due_from" value="{{ $dueFrom }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Due To</label>
                                        <input type="date" name="due_to" value="{{ $dueTo }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200" />
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col gap-3 pt-4">
                                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                    <div class="flex items-center justify-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        <span>Apply Filters</span>
                                    </div>
                                </button>
                                <a href="{{ route('admin.loans.index') }}" class="w-full px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 text-center">
                                    Clear All
                                </a>
                            </div>

                            @php
                                $active = collect([
                                    'q' => $search ?? null,
                                    'status' => $status ?? null,
                                    'user_id' => $userId ?? null,
                                    'issued_from' => $issuedFrom ?? null,
                                    'issued_to' => $issuedTo ?? null,
                                    'due_from' => $dueFrom ?? null,
                                    'due_to' => $dueTo ?? null,
                                ])->filter(fn($v) => filled($v));
                            @endphp
                            @if($active->isNotEmpty())
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        Active Filters
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($active as $key => $val)
                                            @php $q = request()->except($key); @endphp
                                            <a href="{{ route('admin.loans.index', $q) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs bg-gradient-to-r from-purple-100 to-indigo-100 dark:from-purple-900 dark:to-indigo-900 text-purple-700 dark:text-purple-200 hover:from-purple-200 hover:to-indigo-200 dark:hover:from-purple-800 dark:hover:to-indigo-800 transition-all duration-200">
                                                <span>{{ str_replace('_',' ', ucfirst($key)) }}: {{ $val }}</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 hover:scale-110 transition-transform" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 8.586l4.95-4.95 1.414 1.414L11.414 10l4.95 4.95-1.414 1.414L10 11.414l-4.95 4.95-1.414-1.414L8.586 10l-4.95-4.95L5.05 3.636 10 8.586z" clip-rule="evenodd"/></svg>
                                            </a>
                                        @endforeach
                                        <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-full text-xs border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                                            Clear All
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </aside>
                
                <!-- Main Content Area -->
                <section class="lg:w-3/4 w-full">
                    <!-- Modern Table Container -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <!-- Table Header -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Loan Records</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $loans->total() }} total loans</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Showing {{ $loans->firstItem() ?? 0 }}-{{ $loans->lastItem() ?? 0 }} of {{ $loans->total() }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700">
                                    <tr>
                                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/4">
                                            User
                                        </th>
                                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/4">
                                            Book
                                        </th>
                                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/4">
                                            Dates
                                        </th>
                                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/6">
                                            Status
                                        </th>
                                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider w-1/6">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($loans as $loan)
                                        <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 dark:hover:from-gray-700 dark:hover:to-gray-600 transition-all duration-200 group">
                                            <!-- User Column -->
                                            <td class="px-4 py-6">
                                                <div>
                                                    <div class="font-semibold text-gray-900 dark:text-white mb-1">
                                                        <a href="{{ route('admin.loans.show', $loan) }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-200">
                                                            {{ optional($loan->user)->name ?? 'Unknown User' }}
                                                        </a>
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ optional($loan->user)->email ?? 'No email' }}</div>
                                                </div>
                                            </td>
                                            
                                            <!-- Book Column -->
                                            <td class="px-4 py-6">
                                                <div>
                                                    <a href="{{ route('admin.loans.show', $loan) }}" class="font-medium text-gray-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-200 block mb-1">
                                                        @if($loan->book->language && $loan->book->language->code === 'bn' && $loan->book->title_bn)
                                                            {{ $loan->book->title_bn }}
                                                        @else
                                                            {{ $loan->book->title_en }}
                                                        @endif
                                                    </a>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">ISBN: {{ $loan->book->isbn ?? 'N/A' }}</div>
                                                </div>
                                            </td>
                                            
                                            <!-- Dates Column -->
                                            <td class="px-4 py-6">
                                                <div class="space-y-2">
                                                    <div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">Requested</div>
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $loan->requested_at ? \Carbon\Carbon::parse($loan->requested_at)->format('M d, Y') : '-' }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">Issued</div>
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $loan->issued_at ? \Carbon\Carbon::parse($loan->issued_at)->format('M d, Y') : '-' }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">Due</div>
                                                        <div class="text-sm font-medium {{ $loan->due_at && !$loan->returned_at && \Carbon\Carbon::parse($loan->due_at) < now() ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                                            {{ $loan->due_at ? \Carbon\Carbon::parse($loan->due_at)->format('M d, Y') : '-' }}
                                                        </div>
                                                        @if($loan->due_at && !$loan->returned_at && \Carbon\Carbon::parse($loan->due_at) < now())
                                                            <div class="mt-1">
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                                    Overdue
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <!-- Status Column -->
                                            <td class="px-4 py-6">
                                                @php
                                                    $statusConfig = [
                                                        'pending' => ['bg' => 'bg-gradient-to-r from-yellow-100 to-orange-100 dark:from-yellow-900 dark:to-orange-900', 'text' => 'text-yellow-800 dark:text-yellow-200'],
                                                        'issued' => ['bg' => 'bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-blue-900 dark:to-indigo-900', 'text' => 'text-blue-800 dark:text-blue-200'],
                                                        'return_requested' => ['bg' => 'bg-gradient-to-r from-orange-100 to-red-100 dark:from-orange-900 dark:to-red-900', 'text' => 'text-orange-800 dark:text-orange-200'],
                                                        'returned' => ['bg' => 'bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900 dark:to-emerald-900', 'text' => 'text-green-800 dark:text-green-200'],
                                                        'declined' => ['bg' => 'bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600', 'text' => 'text-gray-800 dark:text-gray-200']
                                                    ];
                                                    $config = $statusConfig[$loan->status] ?? $statusConfig['pending'];
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                                    {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
                                                </span>
                                            </td>
                                            
                                            <!-- Actions Column -->
                                            <td class="px-4 py-6">
                                                @if($loan->status !== 'returned')
                                                    @if($loan->status === 'pending')
                                                        <div class="flex flex-col space-y-2">
                                                            <button onclick="openApproveModal({{ $loan->id }})" class="w-full px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white text-sm font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                                                Approve
                                                            </button>
                                                            <form action="{{ route('admin.loans.decline',$loan) }}" method="POST" class="w-full">
                                                                @csrf
                                                                <button class="w-full px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white text-sm font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                                                    Decline
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @elseif($loan->status === 'issued')
                                                        <form action="{{ route('admin.loans.return',$loan) }}" method="POST" class="w-full">
                                                            @csrf
                                                            <button class="w-full px-3 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white text-sm font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                                                Mark Returned
                                                            </button>
                                                        </form>
                                                    @elseif($loan->status === 'return_requested')
                                                        <form action="{{ route('admin.loans.return',$loan) }}" method="POST" class="w-full">
                                                            @csrf
                                                            <button class="w-full px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white text-sm font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                                                Check-in
                                                            </button>
                                                        </form>
                                                    @endif
                                                @else
                                                    <span class="text-sm text-gray-500 dark:text-gray-400 italic">No actions available</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-4">
                            {{ $loans->links() }}
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

    <!-- Modern Approve Modal -->
    <div id="approveModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" onclick="closeApproveModal()"></div>
            
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Approve Loan Request</h3>
                            <p class="text-green-100 text-sm">Set issue and due dates for this loan</p>
                        </div>
                    </div>
                </div>

                <form id="approveForm" method="POST" action="">
                    @csrf
                    <div class="px-6 py-6">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="issued_at" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Issued Date *</span>
                                        </div>
                                    </label>
                                    <input 
                                        type="date" 
                                        id="issued_at" 
                                        name="issued_at" 
                                        value="{{ now()->toDateString() }}" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200" 
                                        required 
                                    />
                                </div>
                                <div>
                                    <label for="due_at" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>Due Date *</span>
                                        </div>
                                    </label>
                                    <input 
                                        type="date" 
                                        id="due_at" 
                                        name="due_at" 
                                        value="{{ now()->addDays(14)->toDateString() }}" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200" 
                                        required 
                                    />
                                </div>
                            </div>
                            
                            <!-- Info Box -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                                <div class="flex items-start space-x-3">
                                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200">Important Note</h4>
                                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                            The due date is automatically set to 14 days from the issue date. You can modify it if needed.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="closeApproveModal()" class="w-full sm:w-auto px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Confirm Approval</span>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openApproveModal(loanId) {
            const modal = document.getElementById('approveModal');
            const form = document.getElementById('approveForm');
            form.action = `/admin/loans/${loanId}/approve`;
            modal.classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('approveModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeApproveModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeApproveModal();
            }
        });
    </script>
</x-app-layout>
