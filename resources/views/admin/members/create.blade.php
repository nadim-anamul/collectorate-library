<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('ui.add_member') }}</h2></x-slot>
    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.members.store') }}" method="POST" class="space-y-4">@csrf
                        <div><label class="block text-sm">{{ __('ui.member_id') }}</label><input name="member_id" class="mt-1 w-full border rounded p-2" required /></div>
                        <div><label class="block text-sm">{{ __('ui.name') }}</label><input name="name" class="mt-1 w-full border rounded p-2" required /></div>
                        <div><label class="block text-sm">{{ __('ui.email_address') }}</label><input name="email" class="mt-1 w-full border rounded p-2" /></div>
                        <div><label class="block text-sm">{{ __('ui.phone') }}</label><input name="phone" class="mt-1 w-full border rounded p-2" /></div>
                        <div><label class="block text-sm">{{ __('ui.type') }}</label><input name="type" value="Member" class="mt-1 w-full border rounded p-2" /></div>
                        <div><label class="block text-sm">{{ __('ui.joined_at') }}</label><input name="joined_at" type="date" class="mt-1 w-full border rounded p-2" /></div>
                        <div class="flex justify-end"><button class="px-4 py-2 bg-indigo-600 text-white rounded">{{ __('ui.save_member') }}</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
