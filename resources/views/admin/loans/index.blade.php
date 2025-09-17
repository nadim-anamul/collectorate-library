<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Loans</h2>
            <a href="{{ route('admin.loans.create') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Issue Book</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="lg:flex lg:gap-6">
                        <aside class="lg:w-1/4 w-full mb-6 lg:mb-0">
                            <form method="GET" action="{{ route('admin.loans.index') }}" class="space-y-4">
                                <div class="flex items-center justify-end">
                                    <a href="{{ route('admin.loans.index') }}" class="text-sm px-3 py-1 rounded border dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Clear Filters</a>
                                </div>
                                <div class="rounded-lg bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 p-4">
                                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                        <svg class="w-4 h-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM1.5 10.5a9 9 0 1116.364 5.682l4.227 4.227a.75.75 0 11-1.06 1.06l-4.227-4.226A9 9 0 011.5 10.5z" clip-rule="evenodd"/></svg>
                                        <span>Search Loans</span>
                                    </div>
                                    <input id="loan-search" type="text" name="q" value="{{ $search }}" placeholder="User, email, book title, ISBN" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600" />
                                </div>
                                <div class="rounded-lg bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold">
                                        <svg class="w-4 h-4 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M5.25 8.25h13.5m-12 3.75h10.5m-9 3.75h7.5M9 19.5h6" /></svg>
                                            <span>Filters</span>
                                        </div>
                                        <label class="inline-flex items-center text-xs cursor-pointer select-none">
                                            <input type="checkbox" name="overdue" value="1" {{ ($overdueOnly ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                            <span class="ml-2 text-red-600 dark:text-red-400">Overdue only</span>
                                        </label>
                                    </div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                                        <option value="">All</option>
                                    @foreach(['pending','issued','return_requested','returned','declined'] as $s)
                                            <option value="{{ $s }}" @selected(($status ?? '')===$s)>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-3">
                                    <label class="block text-sm text-gray-700 dark:text-gray-300">User</label>
                                    <select name="user_id" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                                        <option value="">All</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}" @selected(($userId ?? '')==$u->id)>{{ $u->name }} ({{ $u->email }})</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-3">
                                    <div>
                                        <label class="block text-sm text-gray-700 dark:text-gray-300">Issued From</label>
                                        <input type="date" name="issued_from" value="{{ $issuedFrom }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 dark:text-gray-300">Issued To</label>
                                        <input type="date" name="issued_to" value="{{ $issuedTo }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 dark:text-gray-300">Due From</label>
                                        <input type="date" name="due_from" value="{{ $dueFrom }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 dark:text-gray-300">Due To</label>
                                        <input type="date" name="due_to" value="{{ $dueTo }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 pt-1">
                                    <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">Apply</button>
                                    <a href="{{ route('admin.loans.index') }}" class="px-4 py-2 rounded border dark:border-gray-600">Clear</a>
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
                                    <div class="pt-2">
                                        <div class="text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Active Filters</div>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($active as $key => $val)
                                                @php $q = request()->except($key); @endphp
                                                <a href="{{ route('admin.loans.index', $q) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                                    <span>{{ str_replace('_',' ', ucfirst($key)) }}: {{ $val }}</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 8.586l4.95-4.95 1.414 1.414L11.414 10l4.95 4.95-1.414 1.414L10 11.414l-4.95 4.95-1.414-1.414L8.586 10l-4.95-4.95L5.05 3.636 10 8.586z" clip-rule="evenodd"/></svg>
                                                </a>
                                            @endforeach
                                            <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center px-2 py-1 rounded text-xs border dark:border-gray-600">Clear All</a>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </aside>
                        <section class="lg:w-3/4 w-full">
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr class="text-left text-gray-700 dark:text-gray-200">
                                            <th class="py-2 px-3">User</th>
                                            <th class="py-2 px-3">Book</th>
                                            <th class="py-2 px-3">Requested</th>
                                            <th class="py-2 px-3">Issued</th>
                                            <th class="py-2 px-3">Due</th>
                                            <th class="py-2 px-3">Status</th>
                                            <th class="py-2 px-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($loans as $loan)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="py-2 px-3">
                                                <div class="font-medium">
                                                    <a href="{{ route('admin.loans.show', $loan) }}" class="hover:underline">{{ optional($loan->user)->name }}</a>
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ optional($loan->user)->email }}</div>
                                            </td>
                                    <td class="py-2 px-3">
                                        <a href="{{ route('admin.loans.show', $loan) }}" class="hover:underline">
                                        @if($loan->book->language && $loan->book->language->code === 'bn' && $loan->book->title_bn)
                                            {{ $loan->book->title_bn }}
                                        @else
                                            {{ $loan->book->title_en }}
                                        @endif
                                        </a>
                                    </td>
                                            <td class="py-2 px-3">{{ $loan->requested_at ?? '' }}</td>
                                            <td class="py-2 px-3">{{ $loan->issued_at ?: '-' }}</td>
                                            <td class="py-2 px-3">
                                                {{ $loan->due_at ?: '-' }}
                                                @if($loan->due_at && !$loan->returned_at && \Carbon\Carbon::parse($loan->due_at) < now())
                                                    <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Overdue</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-3">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                                                    {{ $loan->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                    {{ $loan->status === 'issued' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                    {{ $loan->status === 'return_requested' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : '' }}
                                                    {{ $loan->status === 'returned' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                    {{ $loan->status === 'declined' ? 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                                                ">{{ ucfirst($loan->status) }}</span>
                                            </td>
                                            <td class="py-2 px-3">
                                                @if($loan->status !== 'returned')
                                            @if($loan->status === 'pending')
                                                        <div x-data="{ open:false }" class="inline-flex gap-2">
                                                            <button @click="open=true" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded">Approve</button>
                                                            <form action="{{ route('admin.loans.decline',$loan) }}" method="POST" class="inline">@csrf
                                                                <button class="px-2 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded">Decline</button>
                                                            </form>
                                                            <!-- Modal -->
                                                            <div x-cloak x-show="open" class="fixed inset-0 z-50 flex items-center justify-center">
                                                                <div @click="open=false" class="absolute inset-0 bg-black/50"></div>
                                                                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-5">
                                                                    <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">Approve Loan</h3>
                                                                    <form action="{{ route('admin.loans.approve',$loan) }}" method="POST" class="space-y-3">@csrf
                                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                                            <div>
                                                                                <label class="block text-sm text-gray-700 dark:text-gray-300">Issued At</label>
                                                                                <input type="date" name="issued_at" value="{{ now()->toDateString() }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required />
                                                                            </div>
                                                                            <div>
                                                                                <label class="block text-sm text-gray-700 dark:text-gray-300">Due At</label>
                                                                                <input type="date" name="due_at" value="{{ now()->addDays(14)->toDateString() }}" class="mt-1 w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required />
                                                                            </div>
                                                                        </div>
                                                                        <div class="flex justify-end gap-2 pt-2">
                                                                            <button type="button" @click="open=false" class="px-3 py-2 rounded border dark:border-gray-600">Cancel</button>
                                                                            <button class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Confirm Approve</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                            @elseif($loan->status === 'issued')
                                                        <form action="{{ route('admin.loans.return',$loan) }}" method="POST" class="inline">@csrf
                                                            <button class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white rounded">Mark Returned</button>
                                                        </form>
                                            @elseif($loan->status === 'return_requested')
                                                <form action="{{ route('admin.loans.return',$loan) }}" method="POST" class="inline">@csrf
                                                    <button class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white rounded">Check-in</button>
                                                </form>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">{{ $loans->links() }}</div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
