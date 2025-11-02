<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Leads') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex items-center justify-between gap-2">
                    <form method="get" class="flex items-center gap-2">
                        <x-text-input name="q" value="{{ request('q') }}" placeholder="Search name/email/phone" />
                        <select name="status" class="border p-2 rounded">
                            <option value="">All statuses</option>
                            @foreach(['new','in_progress','closed','rejected','won','lost'] as $s)
                                <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                        <select name="per_page" class="border-gray-300 rounded-md">
                            @foreach([10,20,50,100] as $pp)
                                <option value="{{ $pp }}" {{ (int)request('per_page',20)===$pp?'selected':'' }}>{{ $pp }}</option>
                            @endforeach
                        </select>
                        <x-primary-button>Apply</x-primary-button>
                    </form>
                    <a href="{{ route('admin.leads.export') }}?status={{ request('status') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md">Export CSV</a>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                @php $dir = request('dir')==='asc' ? 'desc' : 'asc'; @endphp
                                <th class="px-3 py-2 text-left"><a href="{{ request()->fullUrlWithQuery(['sort'=>'id','dir'=>$dir]) }}">ID</a></th>
                                <th class="px-3 py-2 text-left"><a href="{{ request()->fullUrlWithQuery(['sort'=>'full_name','dir'=>$dir]) }}">Name</a></th>
                                <th class="px-3 py-2 text-left"><a href="{{ request()->fullUrlWithQuery(['sort'=>'email','dir'=>$dir]) }}">Email</a></th>
                                <th class="px-3 py-2 text-left"><a href="{{ request()->fullUrlWithQuery(['sort'=>'created_at','dir'=>$dir]) }}">Mobile</a></th>
                                <th class="px-3 py-2 text-left"><a href="{{ request()->fullUrlWithQuery(['sort'=>'status','dir'=>$dir]) }}">Status</a></th>
                                <th class="px-3 py-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($items as $lead)
                                <tr>
                                    <td class="px-3 py-2">{{ $lead->id }}</td>
                                    <td class="px-3 py-2">{{ $lead->full_name }}</td>
                                    <td class="px-3 py-2">{{ $lead->email }}</td>
                                    <td class="px-3 py-2">{{ $lead->mobile_number }}</td>
                                    <td class="px-3 py-2">{{ $lead->status }}</td>
                                    <td class="px-3 py-2 text-right">
                                        <a href="{{ route('admin.leads.show', $lead) }}" class="text-indigo-700 hover:underline">View</a>
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


