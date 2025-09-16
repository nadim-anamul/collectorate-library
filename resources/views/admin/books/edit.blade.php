<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Book</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.books.update',$book) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm">Title (English)</label>
                            <input name="title_en" value="{{ old('title_en',$book->title_en) }}" class="mt-1 w-full border rounded p-2" required />
                        </div>
                        <div>
                            <label class="block text-sm">Title (Bangla)</label>
                            <input name="title_bn" value="{{ old('title_bn',$book->title_bn) }}" class="mt-1 w-full border rounded p-2" required />
                        </div>
                        <div>
                            <label class="block text-sm">Banglish Transliteration</label>
                            <input name="title_bn_translit" value="{{ old('title_bn_translit',$book->title_bn_translit) }}" class="mt-1 w-full border rounded p-2" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm">Author (EN)</label>
                                <input name="author_en" value="{{ old('author_en',$book->author_en) }}" class="mt-1 w-full border rounded p-2" />
                            </div>
                            <div>
                                <label class="block text-sm">Author (BN)</label>
                                <input name="author_bn" value="{{ old('author_bn',$book->author_bn) }}" class="mt-1 w-full border rounded p-2" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm">Category</label>
                            <select name="category_id" class="mt-1 w-full border rounded p-2">
                                <option value="">-- None --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @if(old('category_id',$book->category_id)==$cat->id) selected @endif>{{ $cat->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm">Publisher (EN)</label>
                                <input name="publisher_en" value="{{ old('publisher_en',$book->publisher_en) }}" class="mt-1 w-full border rounded p-2" />
                            </div>
                            <div>
                                <label class="block text-sm">Publisher (BN)</label>
                                <input name="publisher_bn" value="{{ old('publisher_bn',$book->publisher_bn) }}" class="mt-1 w-full border rounded p-2" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm">ISBN</label>
                                <input name="isbn" value="{{ old('isbn',$book->isbn) }}" class="mt-1 w-full border rounded p-2" />
                            </div>
                            <div>
                                <label class="block text-sm">Barcode</label>
                                <input name="barcode" value="{{ old('barcode',$book->barcode) }}" class="mt-1 w-full border rounded p-2" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm">Publication Year</label>
                                <input name="publication_year" value="{{ old('publication_year',$book->publication_year) }}" class="mt-1 w-full border rounded p-2" />
                            </div>
                            <div>
                                <label class="block text-sm">Pages</label>
                                <input name="pages" value="{{ old('pages',$book->pages) }}" class="mt-1 w-full border rounded p-2" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm">Primary Language</label>
                            <input name="language_primary" value="{{ old('language_primary',$book->language_primary) }}" class="mt-1 w-full border rounded p-2" />
                        </div>
                        <div>
                            <label class="block textsm">Description (EN)</label>
                            <textarea name="description_en" class="mt-1 w-full border rounded p-2">{{ old('description_en',$book->description_en) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm">Description (BN)</label>
                            <textarea name="description_bn" class="mt-1 w-full border rounded p-2">{{ old('description_bn',$book->description_bn) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm">Tags</label>
                            <select name="tags[]" multiple class="mt-1 w-full border rounded p-2">
                                @php $selected = old('tags', $book->tags->pluck('id')->toArray()); @endphp
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" @if(in_array($tag->id,$selected)) selected @endif>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm">Cover Image</label>
                                <input type="file" name="cover" class="mt-1 w-full" />
                            </div>
                            <div>
                                <label class="block text-sm">PDF</label>
                                <input type="file" name="pdf" class="mt-1 w-full" />
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
