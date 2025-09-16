<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::latest()->paginate(15);
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:languages,code',
            'name' => 'required|string|max:255',
        ]);
        Language::create($validated);
        return redirect()->route('admin.languages.index')->with('status','Language created');
    }

    public function edit(Language $language)
    {
        return view('admin.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:languages,code,'.$language->id,
            'name' => 'required|string|max:255',
        ]);
        $language->update($validated);
        return redirect()->route('admin.languages.index')->with('status','Language updated');
    }

    public function destroy(Language $language)
    {
        $language->delete();
        return redirect()->route('admin.languages.index')->with('status','Language deleted');
    }
}


