<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('ui.edit_member') }}</h2></x-slot>
    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.members.update',$member) }}" method="POST" class="space-y-4">@csrf @method('PUT')
                        <div><label class="block text-sm">{{ __('ui.member_id') }}</label><input name="member_id" value="{{ old('member_id',$member->member_id) }}" class="mt-1 w-full border rounded p-2" required /></div>
                        <div><label class="block text-sm">{{ __('ui.name') }}</label><input name="name" value="{{ old('name',$member->name) }}" class="mt-1 w-full border rounded p-2" required /></div>
                        <div><label class="block text-sm">{{ __('ui.email_address') }}</label><input name="email" value="{{ old('email',$member->email) }}" class="mt-1 w-full border rounded p-2" /></div>
                        <div><label class="block text-sm">{{ __('ui.phone') }}</label><input name="phone" value="{{ old('phone',$member->phone) }}" class="mt-1 w-full border rounded p-2" /></div>
                        <div><label class="block text-sm">{{ __('ui.type') }}</label><input name="type" value="{{ old('type',$member->type) }}" class="mt-1 w-full border rounded p-2" /></div>
                        <div><label class="block text-sm">{{ __('ui.joined_at') }}</label><input name="joined_at" type="date" value="{{ old('joined_at',$member->joined_at) }}" class="mt-1 w-full border rounded p-2" /></div>
                        <div><label class="inline-flex items-center space-x-2"><input type="checkbox" name="active" value="1" @if(old('active',$member->active)) checked @endif /><span>{{ __('ui.active') }}</span></label></div>
                        <div class="flex justify-end"><button class="px-4 py-2 bg-indigo-600 text-white rounded">{{ __('ui.update_member') }}</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
