<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Loans</h2>
            <a href="{{ route('admin.loans.create') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Issue Book</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full text-sm">
                        <thead><tr class="text-left"><th class="py-2">Member</th><th class="py-2">Book</th><th class="py-2">Issued</th><th class="py-2">Due</th><th class="py-2">Status</th><th class="py-2">Late Fee</th><th class="py-2">Actions</th></tr></thead>
                        <tbody>
                        @foreach($loans as $loan)
                            <tr class="border-t">
                                <td class="py-2">{{ $loan->member->name }}</td>
                                <td class="py-2">{{ $loan->book->title_en }}</td>
                                <td class="py-2">{{ $loan->issued_at }}</td>
                                <td class="py-2">{{ $loan->due_at }}</td>
                                <td class="py-2">{{ ucfirst($loan->status) }}</td>
                                <td class="py-2">{{ number_format($loan->late_fee,2) }}</td>
                                <td class="py-2">
                                    @if($loan->status !== 'returned')
                                    <form action="{{ route('admin.loans.return',$loan) }}" method="POST">@csrf
                                        <button class="px-2 py-1 bg-green-600 text-white rounded">Mark Returned</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $loans->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
