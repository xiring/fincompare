<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Permissions') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex items-center justify-between">
                    <form method="get" class="flex items-center gap-2">
                        <x-text-input name="q" value="{{ request('q') }}" placeholder="Search name" />
                        <select name="per_page" class="border-gray-300 rounded-md">
                            @foreach([10,20,50,100] as $pp)
                                <option value="{{ $pp }}" {{ (int)request('per_page',20)===$pp?'selected':'' }}>{{ $pp }}</option>
                            @endforeach
                        </select>
                        <x-primary-button>Apply</x-primary-button>
                    </form>
                    <a href="{{ route('admin.permissions.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md">New Permission</a>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                @php $dir = request('dir')==='asc' ? 'desc' : 'asc'; @endphp
                                <th class="px-3 py-2 text-left"><a href="{{ request()->fullUrlWithQuery(['sort'=>'id','dir'=>$dir]) }}">ID</a></th>
                                <th class="px-3 py-2 text-left"><a href="{{ request()->fullUrlWithQuery(['sort'=>'name','dir'=>$dir]) }}">Name</a></th>
                                <th class="px-3 py-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($items as $permission)
                                <tr>
                                    <td class="px-3 py-2">{{ $permission->id }}</td>
                                    <td class="px-3 py-2">{{ $permission->name }}</td>
                                    <td class="px-3 py-2 text-right space-x-2">
                                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-indigo-700 hover:underline">Edit</a>
                                        <form action="{{ route('admin.permissions.destroy', $permission) }}" method="post" class="inline" onsubmit="return confirm('Delete this permission?')">
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


