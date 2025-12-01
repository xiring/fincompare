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
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-primary-600 hover:text-primary-900 transition-colors">Edit</a>
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="post" class="inline" onsubmit="return confirm('Delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">Delete</button>
                                    </form>
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


