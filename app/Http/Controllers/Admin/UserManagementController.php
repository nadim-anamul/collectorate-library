<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use App\Mail\UserApproved;
use App\Mail\UserRejected;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('member_type')) {
            $query->where('member_type', $request->member_type);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('email', 'like', '%'.$search.'%')
                  ->orWhere('phone', 'like', '%'.$search.'%');
            });
        }
        
        $users = $query->with(['roles', 'approvedBy'])
                      ->latest()
                      ->paginate(15)
                      ->appends($request->query());
        
        $pendingCount = User::where('status', 'pending')->count();
        $approvedCount = User::where('status', 'approved')->count();
        $rejectedCount = User::where('status', 'rejected')->count();
        
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'pendingCount', 'approvedCount', 'rejectedCount', 'roles'));
    }
    
    public function approve(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);
        
        $user->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);
        
        $user->assignRole($request->role);
        
        // Send approval email notification
        try {
            Mail::to($user->email)->send(new UserApproved($user, $request->role));
        } catch (\Exception $e) {
            // Log the error but don't fail the approval process
            \Log::error('Failed to send approval email: ' . $e->getMessage());
        }
        
        return back()->with('success', "User {$user->name} has been approved and assigned the {$request->role} role.");
    }
    
    public function reject(Request $request, User $user)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        $user->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => Auth::id(),
        ]);
        
        // Send rejection email notification
        try {
            Mail::to($user->email)->send(new UserRejected($user, $request->rejection_reason));
        } catch (\Exception $e) {
            // Log the error but don't fail the rejection process
            \Log::error('Failed to send rejection email: ' . $e->getMessage());
        }
        
        return back()->with('success', "User {$user->name} has been rejected.");
    }
    
    public function show(User $user)
    {
        $user->load(['roles', 'approvedBy']);
        return view('admin.users.show', compact('user'));
    }
    
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);
        
        $user->syncRoles([$request->role]);
        
        return back()->with('success', "User role updated to {$request->role}.");
    }
    
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        
        return back()->with('success', "User {$user->name} has been deleted.");
    }
}