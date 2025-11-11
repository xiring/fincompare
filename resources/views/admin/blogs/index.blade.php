<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Blogs') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex items-center justify-between">
                    <form method="get" class="flex flex-wrap items-center gap-2">
                        <x-text-input name="q" value="{{ request('q') }}" placeholder="Search by title" />
                        <select name="status" class="border-gray-300 rounded-md">
                            <option value="">Any status</option>
                            @foreach(['draft','published'] as $st)
                                <option value="{{ $st }}" {{ request('status')===$st?'selected':'' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                        <select name="sort" class="border-gray-300 rounded-md">
                            @php $sort = request('sort','created_at'); @endphp
                            @foreach(['created_at'=>'Created','title'=>'Title','status'=>'Status','id'=>'ID'] as $k=>$v)
                                <option value="{{ $k }}" {{ $sort===$k?'selected':'' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                        <select name="dir" class="border-gray-300 rounded-md">
                            @php $dir = request('dir','desc'); @endphp
                            <option value="asc" {{ $dir==='asc'?'selected':'' }}>Asc</option>
                            <option value="desc" {{ $dir==='desc'?'selected':'' }}>Desc</option>
                        </select>
                        <select name="per_page" class="border-gray-300 rounded-md">
                            @php $pp = (int)request('per_page',20); @endphp
                            @foreach([10,20,50,100] as $n)
                                <option value="{{ $n }}" {{ $pp===$n?'selected':'' }}>{{ $n }}/page</option>
                            @endforeach
                        </select>
                        <x-primary-button>Apply</x-primary-button>
                    </form>
                    <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md">New</a>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-3 py-2 text-left">Title</th>
                                <th class="px-3 py-2 text-left">Category</th>
                                <th class="px-3 py-2 text-left">Status</th>
                                <th class="px-3 py-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($items as $item)
                                <tr>
                                    <td class="px-3 py-2">{{ $item->title }}</td>
                                    <td class="px-3 py-2">{{ $item->category }}</td>
                                    <td class="px-3 py-2">{{ $item->status }}</td>
                                    <td class="px-3 py-2 text-right space-x-2">
                                        <a href="{{ route('admin.blogs.edit', $item) }}" class="text-indigo-700 hover:underline">Edit</a>
                                        <form action="{{ route('admin.blogs.destroy', $item) }}" method="post" class="inline" onsubmit="return confirm('Delete this post?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-700 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $items->withQueryString()->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


