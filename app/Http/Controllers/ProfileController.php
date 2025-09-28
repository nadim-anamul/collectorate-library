<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('profile.show', ['user' => $request->user()]);
    }

    public function edit(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email,'.$user->id],
            'phone' => ['nullable','string','max:20'],
            'address' => ['nullable','string','max:500'],
            'job_post' => ['nullable','string','max:100'],
            'password' => ['nullable','confirmed', \Illuminate\Validation\Rules\Password::min(8)->letters()->mixedCase()->numbers()],
        ]);

        $user->fill(collect($validated)->except(['password','password_confirmation'])->toArray());
        if ($request->filled('password')) {
            $user->password = \Hash::make($request->input('password'));
        }
        $user->save();

        return redirect()->route('profile.show')->with('status','Profile updated successfully');
    }
}


