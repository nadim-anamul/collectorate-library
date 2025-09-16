<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Member</h2></x-slot>
    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.members.update',$member) }}" method="POST" class="space-y-4">@csrf @method('PUT')
                        <div><label class="block text-sm">Member ID</label><input name="member_id" value="{{ old('member_id',$member->member_id) }}" class="mt-1 w-full border rounded p-2" required /></div>
                        <div><label class="block text-sm">Name</label><input name="name" value="{{ old('name',$member->name) }}" class="mt-1 w-full border rounded p-2" required /></div>
                        <div><label class="block text-sm">Email</label><input name="email" value="{{ old('email',$member->email) }}" class="mt-1 w-full border rounded p-2" /></div>
                        <div><label class="block text-sm">Phone</label><input name="phone" value="{{ old('phone',$member->phone) }}" class="mt-1 w-full border rounded p-2" /></div>
                        <div><label class="block text-sm">Type</label><input name="type" value="{{ old('type',$member->type) }}" class="mt-1 w-full border rounded p-2" /></div>
                        <div><label class="block text-sm">Joined At</label><input name="joined_at" type="date" value="{{ old('joined_at',$member->joined_at) }}" class="mt-1 w-full border rounded p-2" /></div>
                        <div><label class="inline-flex items-center space-x-2"><input type="checkbox" name="active" value="1" @if(old('active',$member->active)) checked @endif /><span>Active</span></label></div>
                        <div class="flex justify-end"><button class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
