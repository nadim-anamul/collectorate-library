<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->latest()->limit(15)->get();
        $unreadCount = $user->unreadNotifications()->count();
        return response()->json([
            'unread' => $unreadCount,
            'items' => $notifications->map(function($n){
                return [
                    'id' => $n->id,
                    'read_at' => $n->read_at,
                    'created_at' => $n->created_at->toDateTimeString(),
                    'data' => $n->data,
                ];
            }),
        ]);
    }

    public function all(Request $request)
    {
        $user = $request->user();
        $q = $user->notifications()->latest();
        $notifications = $q->paginate(30)->withQueryString();
        $unreadCount = $user->unreadNotifications()->count();
        return view('notifications.index', compact('notifications','unreadCount'));
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'id' => 'nullable|string',
        ]);
        $user = $request->user();
        if ($request->filled('id')) {
            $user->notifications()->where('id',$request->input('id'))->update(['read_at' => now()]);
        } else {
            $user->unreadNotifications->markAsRead();
        }
        return response()->noContent();
    }
}


