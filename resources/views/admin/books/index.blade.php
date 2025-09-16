<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Books</h2>
            <a href="{{ route('admin.books.create') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Add Book</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Search title/author (EN/BN)" class="border rounded p-2 w-full" autocomplete="off" />
                            <div id="suggestions" class="bg-white border rounded mt-1 hidden"></div>
                        </div>
                        <input type="text" name="banglish" value="{{ request('banglish') }}" placeholder="Banglish e.g. Shesher Kobita" class="border rounded p-2" />
                        <input type="text" name="isbn" value="{{ request('isbn') }}" placeholder="ISBN" class="border rounded p-2" />
                        <button class="px-3 py-2 bg-gray-800 text-white rounded">Filter</button>
                    </form>
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left">
                                <th class="py-2">Title (EN)</th>
                                <th class="py-2">Title (BN)</th>
                                <th class="py-2">Category</th>
                                <th class="py-2">ISBN</th>
                                <th class="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($books as $book)
                            <tr class="border-t">
                                <td class="py-2">{{ $book->title_en }}</td>
                                <td class="py-2">{{ $book->title_bn }}</td>
                                <td class="py-2">{{ optional($book->category)->name_en }}</td>
                                <td class="py-2">{{ $book->isbn }}</td>
                                <td class="py-2 space-x-2">
                                    <a href="{{ route('admin.books.edit',$book) }}" class="text-indigo-600">Edit</a>
                                    <form action="{{ route('admin.books.destroy',$book) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600" onclick="return confirm('Delete?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $books->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const q = document.getElementById('q');
  const box = document.getElementById('suggestions');
  let timer;
  q.addEventListener('input', function(){
    clearTimeout(timer);
    const val = q.value.trim();
    if(!val){ box.classList.add('hidden'); box.innerHTML=''; return; }
    timer = setTimeout(async () => {
      const res = await fetch('{{ route('suggest.books') }}?q=' + encodeURIComponent(val), {headers:{'X-Requested-With':'XMLHttpRequest'}});
      const data = await res.json();
      box.innerHTML = data.map(d => `<div class=\"px-2 py-1 hover:bg-gray-100 cursor-pointer\">${d.title_en} / ${d.title_bn}</div>`).join('');
      box.classList.remove('hidden');
    }, 250);
  });
  document.addEventListener('click', function(e){ if(!box.contains(e.target) && e.target !== q){ box.classList.add('hidden'); }});
});
</script>
