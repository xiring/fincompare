<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('FAQs') }}</h2>
                <p class="mt-1 text-sm text-gray-600">Manage frequently asked questions</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <form method="get" class="flex flex-wrap items-center gap-3">
                    <x-text-input name="q" value="{{ request('q') }}" placeholder="Search..." class="min-w-[200px]" />
                    <select name="sort" class="border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        @php $sort = request('sort','created_at'); @endphp
                        @foreach(['created_at'=>'Created','question'=>'Question','id'=>'ID'] as $k=>$v)
                            <option value="{{ $k }}" {{ $sort===$k?'selected':'' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    <select name="dir" class="border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        @php $dir = request('dir','desc'); @endphp
                        <option value="asc" {{ $dir==='asc'?'selected':'' }}>Asc</option>
                        <option value="desc" {{ $dir==='desc'?'selected':'' }}>Desc</option>
                    </select>
                    <select name="per_page" class="border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        @php $pp = (int)request('per_page',20); @endphp
                        @foreach([10,20,50,100] as $n)
                            <option value="{{ $n }}" {{ $pp===$n?'selected':'' }}>{{ $n }} per page</option>
                        @endforeach
                    </select>
                    <x-primary-button>Apply</x-primary-button>
                </form>
                <a href="{{ route('admin.faqs.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 transition-colors">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New FAQ
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-soft border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Question</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items as $faq)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $faq->question }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.faqs.edit', $faq) }}" title="Edit" class="inline-flex items-center justify-center p-2 text-primary-600 hover:text-primary-900 hover:bg-primary-50 rounded-lg transition-colors">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.faqs.destroy', $faq) }}" method="post" class="inline" onsubmit="return confirm('Delete this item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete" class="inline-flex items-center justify-center p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
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


