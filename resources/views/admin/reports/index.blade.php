<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('ui.reports') }}</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="GET" class="bg-white p-4 rounded shadow inline-block">
                <label class="mr-2">{{ __('ui.range') }}</label>
                <select name="range" class="border rounded p-2">
                    <option value="daily" @if($range==='daily') selected @endif>{{ __('ui.daily') }}</option>
                    <option value="weekly" @if($range==='weekly') selected @endif>{{ __('ui.weekly') }}</option>
                    <option value="monthly" @if($range==='monthly') selected @endif>{{ __('ui.monthly') }}</option>
                </select>
                <button class="ml-2 px-3 py-2 bg-gray-800 text-white rounded">{{ __('ui.apply') }}</button>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-4 rounded shadow"><div class="text-gray-500">{{ __('ui.issued_count') }} ({{ $range }})</div><div class="text-3xl font-semibold">{{ $issuedCount }}</div></div>
                <div class="bg-white p-4 rounded shadow"><div class="text-gray-500">{{ __('ui.popular_books') }}</div><ul class="mt-2">@foreach($popularBooks as $pb)<li>{{ optional($pb->book)->title_en }} ({{ $pb->cnt }})</li>@endforeach</ul></div>
                <div class="bg-white p-4 rounded shadow"><div class="text-gray-500">{{ __('ui.by_category') }}</div><ul class="mt-2">@foreach($categoryCounts as $cc)<li>{{ optional($cc->category)->name_en }} ({{ $cc->cnt }})</li>@endforeach</ul></div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
                <div class="font-semibold mb-2">{{ __('ui.recent_late_returns') }}</div>
                <table class="min-w-full text-sm">
                    <thead><tr class="text-left text-gray-700 dark:text-gray-300"><th class="py-2">{{ __('ui.user') }}</th><th class="py-2">{{ __('ui.late_fee') }}</th><th class="py-2">{{ __('ui.returned') }}</th></tr></thead>
                    <tbody>
                    @foreach($lateLoans as $loan)
                        <tr class="border-t border-gray-200 dark:border-gray-700"><td class="py-2">{{ optional($loan->user)->name }}</td><td class="py-2">{{ number_format($loan->late_fee,2) }}</td><td class="py-2">{{ $loan->returned_at }}</td></tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
