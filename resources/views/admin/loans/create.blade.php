<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Issue Book</h2></x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.loans.store') }}" method="POST" class="space-y-4">@csrf
                        <div>
                            <label class="block text-sm">Member</label>
                            <select name="member_id" class="mt-1 w-full border rounded p-2" required>
                                @foreach($members as $m)
                                    <option value="{{ $m->id }}">{{ $m->member_id }} - {{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm">Book</label>
                            <select name="book_id" class="mt-1 w-full border rounded p-2" required>
                                @foreach($books as $b)
                                    <option value="{{ $b->id }}">{{ $b->title_en }} ({{ $b->available_copies }}/{{ $b->total_copies }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm">Issued At</label>
                                <input type="date" name="issued_at" value="{{ now()->toDateString() }}" class="mt-1 w-full border rounded p-2" required />
                            </div>
                            <div>
                                <label class="block text-sm">Due At</label>
                                <input type="date" name="due_at" value="{{ now()->addDays(14)->toDateString() }}" class="mt-1 w-full border rounded p-2" required />
                            </div>
                        </div>
                        <div class="flex justify-end"><button class="px-4 py-2 bg-indigo-600 text-white rounded">Issue</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
