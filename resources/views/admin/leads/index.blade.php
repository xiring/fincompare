<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Leads') }}</h2>
                <p class="mt-1 text-sm text-gray-600">Manage and track customer leads</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <form method="get" class="flex flex-wrap items-center gap-3">
                    <x-text-input name="q" value="{{ request('q') }}" placeholder="Search name/email/phone" class="min-w-[200px]" />
                    <select name="status" class="border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        <option value="">All statuses</option>
                        @foreach(['new','in_progress','closed','rejected','won','lost'] as $s)
                            <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                        @endforeach
                    </select>
                    <select name="per_page" class="border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        @foreach([10,20,50,100] as $pp)
                            <option value="{{ $pp }}" {{ (int)request('per_page',20)===$pp?'selected':'' }}>{{ $pp }} per page</option>
                        @endforeach
                    </select>
                    <x-primary-button>Apply</x-primary-button>
                </form>
                <a href="{{ route('admin.leads.export') }}?status={{ request('status') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 text-white rounded-lg font-medium text-sm hover:bg-emerald-700 transition-colors">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export CSV
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-soft border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @php $dir = request('dir')==='asc' ? 'desc' : 'asc'; @endphp
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort'=>'id','dir'=>$dir]) }}" class="hover:text-primary-600 transition-colors">ID</a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort'=>'full_name','dir'=>$dir]) }}" class="hover:text-primary-600 transition-colors">Name</a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort'=>'email','dir'=>$dir]) }}" class="hover:text-primary-600 transition-colors">Email</a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort'=>'created_at','dir'=>$dir]) }}" class="hover:text-primary-600 transition-colors">Mobile</a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort'=>'status','dir'=>$dir]) }}" class="hover:text-primary-600 transition-colors">Status</a>
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items as $lead)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $lead->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $lead->full_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lead->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lead->mobile_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'new' => 'bg-blue-100 text-blue-800',
                                            'in_progress' => 'bg-yellow-100 text-yellow-800',
                                            'closed' => 'bg-gray-100 text-gray-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'won' => 'bg-green-100 text-green-800',
                                            'lost' => 'bg-red-100 text-red-800',
                                        ];
                                        $color = $statusColors[$lead->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                        {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.leads.show', $lead) }}" class="text-primary-600 hover:text-primary-900 transition-colors">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $items->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>


