<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordChangeController extends Controller
{
    public function create()
    {
        return view('auth.force-password-change');
    }

    public function store(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);
        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->force_password_reset = false;
        $user->save();
        return redirect()->intended('/');
    }
}
