<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Models\Book;
use App\Models\Models\Category;
use App\Models\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogger;

class BookController extends Controller
{
    public function index()
    {
        $query = Book::with('category')->latest();
        if(request('q')){
            $q = request('q');
            $query->where(function($w) use ($q){
                $w->where('title_en','like','%'.$q.'%')
                  ->orWhere('title_bn','like','%'.$q.'%')
                  ->orWhere('author_en','like','%'.$q.'%')
                  ->orWhere('author_bn','like','%'.$q.'%');
            });
        }
        if(request('banglish')){
            $b = request('banglish');
            $query->where('title_bn_translit','like','%'.$b.'%');
        }
        if(request('isbn')){
            $query->where('isbn','like','%'.request('isbn').'%');
        }
        $books = $query->paginate(15)->appends(request()->query());
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::orderBy('name_en')->get();
        $tags = Tag::orderBy('name')->get();
        return view('admin.books.create', compact('categories','tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'required|string|max:255',
            'title_bn_translit' => 'nullable|string|max:255',
            'author_en' => 'nullable|string|max:255',
            'author_bn' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'publisher_en' => 'nullable|string|max:255',
            'publisher_bn' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn',
            'barcode' => 'nullable|string|unique:books,barcode',
            'publication_year' => 'nullable|digits:4',
            'pages' => 'nullable|integer',
            'language_primary' => 'nullable|string|max:10',
            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:10240',
            'tags' => 'array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        if($request->hasFile('cover')){
            $validated['cover_path'] = $request->file('cover')->store('covers', 'public');
        }
        if($request->hasFile('pdf')){
            $validated['pdf_path'] = $request->file('pdf')->store('pdfs', 'public');
        }

        $book = Book::create($validated);
        if(isset($validated['tags'])){
            $book->tags()->sync($validated['tags']);
        }

        ActivityLogger::log('book.created','Book',$book->id,['title_en' => $book->title_en]);
        return redirect()->route('admin.books.index')->with('status','Book created');
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name_en')->get();
        $tags = Tag::orderBy('name')->get();
        $book->load('tags');
        return view('admin.books.edit', compact('book','categories','tags'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'required|string|max:255',
            'title_bn_translit' => 'nullable|string|max:255',
            'author_en' => 'nullable|string|max:255',
            'author_bn' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'publisher_en' => 'nullable|string|max:255',
            'publisher_bn' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn,'.$book->id,
            'barcode' => 'nullable|string|unique:books,barcode,'.$book->id,
            'publication_year' => 'nullable|digits:4',
            'pages' => 'nullable|integer',
            'language_primary' => 'nullable|string|max:10',
            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:10240',
            'tags' => 'array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        if($request->hasFile('cover')){
            if($book->cover_path){ Storage::disk('public')->delete($book->cover_path); }
            $validated['cover_path'] = $request->file('cover')->store('covers', 'public');
        }
        if($request->hasFile('pdf')){
            if($book->pdf_path){ Storage::disk('public')->delete($book->pdf_path); }
            $validated['pdf_path'] = $request->file('pdf')->store('pdfs', 'public');
        }

        $book->update($validated);
        if(isset($validated['tags'])){
            $book->tags()->sync($validated['tags']);
        }
        ActivityLogger::log('book.updated','Book',$book->id,['title_en' => $book->title_en]);
        return redirect()->route('admin.books.index')->with('status','Book updated');
    }

    public function destroy(Book $book)
    {
        if($book->cover_path){ Storage::disk('public')->delete($book->cover_path); }
        if($book->pdf_path){ Storage::disk('public')->delete($book->pdf_path); }
        $book->tags()->detach();
        $book->delete();
        ActivityLogger::log('book.deleted','Book',$book->id,['title_en' => $book->title_en]);
        return back()->with('status','Book deleted');
    }
}
