<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Models\Member;
use Illuminate\Http\Request;
use App\Services\ActivityLogger;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::orderBy('name')->paginate(20);
        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        return view('admin.members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|string|max:255|unique:members,member_id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'type' => 'required|string|max:50',
            'joined_at' => 'nullable|date',
        ]);

        $member = Member::create($validated + ['active' => true]);
        ActivityLogger::log('member.created','Member',$member->id,['member_id' => $member->member_id]);
        return redirect()->route('admin.members.index')->with('status','Member created');
    }

    public function edit(Member $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'member_id' => 'required|string|max:255|unique:members,member_id,'.$member->id,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'type' => 'required|string|max:50',
            'joined_at' => 'nullable|date',
            'active' => 'nullable|boolean',
        ]);

        $member->update($validated);
        ActivityLogger::log('member.updated','Member',$member->id,['member_id' => $member->member_id]);
        return redirect()->route('admin.members.index')->with('status','Member updated');
    }

    public function destroy(Member $member)
    {
        $id = $member->id; $mid = $member->member_id; $member->delete();
        ActivityLogger::log('member.deleted','Member',$id,['member_id' => $mid]);
        return back()->with('status','Member deleted');
    }

    public function card(Member $member)
    {
        return view('admin.members.card', compact('member'));
    }
}
