<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('name')->paginate(20);
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug',
        ]);

        Tag::create($validated);
        return redirect()->route('admin.tags.index')->with('status','Tag created');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug,'.$tag->id,
        ]);

        $tag->update($validated);
        return redirect()->route('admin.tags.index')->with('status','Tag updated');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return back()->with('status','Tag deleted');
    }
}
