<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Attributes') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex items-center justify-between gap-2">
                    <form method="get" class="flex items-center gap-2">
                        <x-text-input name="q" value="{{ request('q') }}" placeholder="Search name" />
                        <select name="product_category_id" class="border p-2 rounded">
                            <option value="">All categories</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ (string)request('product_category_id')===(string)$c->id?'selected':'' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <select name="per_page" class="border-gray-300 rounded-md">
                            @foreach([10,20,50,100] as $pp)
                                <option value="{{ $pp }}" {{ (int)request('per_page',20)===$pp?'selected':'' }}>{{ $pp }}</option>
                            @endforeach
                        </select>
                        <x-primary-button>Apply</x-primary-button>
                    </form>
                    <a href="{{ route('admin.attributes.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md">New Attribute</a>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                @php $dir = request('dir')==='asc' ? 'desc' : 'asc'; @endphp
                                <th class="px-3 py-2 text-left">Category</th>
                                <th class="px-3 py-2 text-left"><a href="{{ request()->fullUrlWithQuery(['sort'=>'name','dir'=>$dir]) }}">Name</a></th>
                                <th class="px-3 py-2 text-left"><a href="{{ request()->fullUrlWithQuery(['sort'=>'data_type','dir'=>$dir]) }}">Type</a></th>
                                <th class="px-3 py-2 text-left">Filterable</th>
                                <th class="px-3 py-2 text-left">Required</th>
                                <th class="px-3 py-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($items as $attr)
                                <tr>
                                    <td class="px-3 py-2">{{ optional($attr->productCategory)->name }}</td>
                                    <td class="px-3 py-2">{{ $attr->name }}</td>
                                    <td class="px-3 py-2">{{ $attr->data_type }}</td>
                                    <td class="px-3 py-2">{{ $attr->is_filterable ? 'Yes' : 'No' }}</td>
                                    <td class="px-3 py-2">{{ $attr->is_required ? 'Yes' : 'No' }}</td>
                                    <td class="px-3 py-2 text-right space-x-2">
                                        <a href="{{ route('admin.attributes.edit', $attr) }}" class="text-indigo-700 hover:underline">Edit</a>
                                        <form action="{{ route('admin.attributes.destroy', $attr) }}" method="post" class="inline" onsubmit="return confirm('Delete this attribute?')">
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


