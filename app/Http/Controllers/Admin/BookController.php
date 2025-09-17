<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Models\Book;
use App\Models\Models\Category;
use App\Models\Models\Tag;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogger;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function index(Request $request)
    {
        // Start with all books (no default availability filter for admin)
        $query = Book::with(['category', 'tags', 'language', 'primaryAuthor', 'publisher']);
        
        // Search functionality
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                  ->orWhere('title_bn', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('description_en', 'like', "%{$search}%")
                  ->orWhere('description_bn', 'like', "%{$search}%")
                  ->orWhereHas('primaryAuthor', function($subQ) use ($search) {
                      $subQ->where('name_en', 'like', "%{$search}%")
                           ->orWhere('name_bn', 'like', "%{$search}%");
                  })
                  ->orWhereHas('publisher', function($subQ) use ($search) {
                      $subQ->where('name_en', 'like', "%{$search}%")
                           ->orWhere('name_bn', 'like', "%{$search}%");
                  })
                  ->orWhereHas('category', function($subQ) use ($search) {
                      $subQ->where('name_en', 'like', "%{$search}%")
                           ->orWhere('name_bn', 'like', "%{$search}%");
                  })
                  ->orWhereHas('tags', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Banglish filter removed - column no longer exists
        
        // ISBN filter
        if ($request->filled('isbn')) {
            $query->where('isbn', 'like', '%' . $request->isbn . '%');
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Language filter
        if ($request->filled('language')) {
            $query->whereHas('language', function($q) use ($request) {
                $q->where('code', $request->language);
            });
        }
        
        // Year filter
        if ($request->filled('year')) {
            $query->where('publication_year', $request->year);
        }
        
        // Availability filter
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('available_copies', '>', 0);
            } elseif ($request->availability === 'unavailable') {
                $query->where('available_copies', '=', 0);
            }
            // 'all' means no availability filter
        }
        
        // Sort options
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'title':
                $query->orderBy('title_en');
                break;
            case 'author':
                $query->join('authors', 'books.primary_author_id', '=', 'authors.id')
                      ->orderBy('authors.name_en');
                break;
            case 'year':
                $query->orderBy('publication_year', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $books = $query->paginate(15)->appends($request->query());
        
        // Get filter data with caching
        $categories = cache()->remember('categories_list', 3600, function () {
            return Category::orderBy('name_en')->get();
        });
        
        $languages = cache()->remember('languages_list', 3600, function () {
            return Language::orderBy('name')->get();
        });
        
        $years = cache()->remember('publication_years_list', 1800, function () {
            return Book::select('publication_year')
                ->distinct()
                ->whereNotNull('publication_year')
                ->orderBy('publication_year', 'desc')
                ->pluck('publication_year');
        });
        
        return view('admin.books.index', compact('books', 'categories', 'languages', 'years'));
    }

    public function create()
    {
        $categories = Category::orderBy('name_en')->get();
        $tags = Tag::orderBy('name')->get();
        $authors = Author::orderBy('name_en')->get();
        $publishers = Publisher::orderBy('name_en')->get();
        $languages = Language::orderBy('name')->get();
        return view('admin.books.create', compact('categories','tags','authors','publishers','languages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'isbn' => 'nullable|string|max:20|unique:books,isbn',
            'publication_year' => 'nullable|digits:4',
            'pages' => 'nullable|integer',
            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
            'primary_author_id' => 'nullable|exists:authors,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'language_id' => 'nullable|exists:languages,id',
            'authors' => 'array',
            'authors.*' => 'integer|exists:authors,id',
            'tags' => 'array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        if($request->hasFile('cover')){
            $validated['cover_path'] = $this->optimizeAndStoreImage($request->file('cover'));
        }
        // PDF upload removed - column no longer exists

        $book = Book::create($validated);
        if(isset($validated['authors'])){
            $book->authors()->sync($validated['authors']);
        }
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
        $authors = Author::orderBy('name_en')->get();
        $publishers = Publisher::orderBy('name_en')->get();
        $languages = Language::orderBy('name')->get();
        $book->load(['tags','authors']);
        return view('admin.books.edit', compact('book','categories','tags','authors','publishers','languages'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'isbn' => 'nullable|string|max:20|unique:books,isbn,'.$book->id,
            'publication_year' => 'nullable|digits:4',
            'pages' => 'nullable|integer',
            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
            'primary_author_id' => 'nullable|exists:authors,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'language_id' => 'nullable|exists:languages,id',
            'authors' => 'array',
            'authors.*' => 'integer|exists:authors,id',
            'tags' => 'array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        if($request->hasFile('cover')){
            if($book->cover_path){ Storage::disk('public')->delete($book->cover_path); }
            $validated['cover_path'] = $this->optimizeAndStoreImage($request->file('cover'));
        }
        // PDF upload removed - column no longer exists

        $book->update($validated);
        if(isset($validated['authors'])){
            $book->authors()->sync($validated['authors']);
        }
        if(isset($validated['tags'])){
            $book->tags()->sync($validated['tags']);
        }
        ActivityLogger::log('book.updated','Book',$book->id,['title_en' => $book->title_en]);
        return redirect()->route('admin.books.index')->with('status','Book updated');
    }

    public function destroy(Book $book)
    {
        if($book->cover_path){ Storage::disk('public')->delete($book->cover_path); }
        $book->tags()->detach();
        $book->delete();
        ActivityLogger::log('book.deleted','Book',$book->id,['title_en' => $book->title_en]);
        return back()->with('status','Book deleted');
    }

    /**
     * Optimize and store image
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    private function optimizeAndStoreImage($file)
    {
        try {
            // Create image manager with GD driver
            $manager = new ImageManager(new Driver());
            
            // Read the image
            $image = $manager->read($file->getPathname());
            
            // Get original dimensions
            $width = $image->width();
            $height = $image->height();
            
            // Calculate new dimensions (max 500px while maintaining aspect ratio)
            $maxSize = 500;
            
            if ($width > $maxSize || $height > $maxSize) {
                if ($width > $height) {
                    $newWidth = $maxSize;
                    $newHeight = (int) ($height * $maxSize / $width);
                } else {
                    $newHeight = $maxSize;
                    $newWidth = (int) ($width * $maxSize / $height);
                }
                
                // Resize the image
                $image->resize($newWidth, $newHeight);
            }
            
            // Optimize quality (85% for good quality with smaller file size)
            $image->toJpeg(85);
            
            // Generate unique filename
            $filename = 'cover_' . time() . '_' . uniqid() . '.jpg';
            $path = 'covers/' . $filename;
            
            // Save the optimized image
            $image->save(storage_path('app/public/' . $path));
            
            return $path;
            
        } catch (\Exception $e) {
            // Log the error and fall back to original file
            Log::warning('Image optimization failed: ' . $e->getMessage());
            return $file->store('covers', 'public');
        }
    }
}
