<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Products') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex items-center justify-between">
                    <form method="get" class="flex items-center gap-2">
                        <x-text-input name="q" value="{{ request('q') }}" placeholder="Search by name" />
                        <select name="per_page" class="border-gray-300 rounded-md">
                            @foreach([10,20,50,100] as $pp)
                                <option value="{{ $pp }}" {{ (int)request('per_page',20)===$pp?'selected':'' }}>{{ $pp }}</option>
                            @endforeach
                        </select>
                        <x-primary-button>Apply</x-primary-button>
                    </form>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.products.import') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md">Import</a>
                        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md">New Product</a>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                @php $dir = request('dir')==='asc' ? 'desc' : 'asc'; @endphp
                                <th class="px-3 py-2 text-left"><a href="{{ request()->fullUrlWithQuery(['sort'=>'name','dir'=>$dir]) }}">Name</a></th>
                                <th class="px-3 py-2 text-left">Category</th>
                                <th class="px-3 py-2 text-left">Partner</th>
                                <th class="px-3 py-2 text-left"><a href="{{ request()->fullUrlWithQuery(['sort'=>'status','dir'=>$dir]) }}">Status</a></th>
                                <th class="px-3 py-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($items as $item)
                                <tr>
                                    <td class="px-3 py-2">{{ $item->name }}</td>
                                    <td class="px-3 py-2">{{ optional($item->productCategory)->name }}</td>
                                    <td class="px-3 py-2">{{ optional($item->partner)->name }}</td>
                                    <td class="px-3 py-2">{{ $item->status }}</td>
                                    <td class="px-3 py-2 text-right space-x-2">
                                        <a href="{{ route('admin.products.edit', $item) }}" class="text-indigo-700 hover:underline">Edit</a>
                                        <form action="{{ route('admin.products.destroy', $item) }}" method="post" class="inline" onsubmit="return confirm('Delete this product?')">
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


