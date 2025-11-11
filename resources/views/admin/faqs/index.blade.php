<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('FAQs') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <form method="get" class="flex gap-2">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search..." class="border rounded-md px-3 py-2" />
                        <x-primary-button>Filter</x-primary-button>
                    </form>
                    <a href="{{ route('admin.faqs.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md">New FAQ</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($items as $faq)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $faq->question }}</td>
                                    <td class="px-4 py-2 text-right text-sm">
                                        <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-indigo-600 hover:underline">Edit</a>
                                        <form action="{{ route('admin.faqs.destroy', $faq) }}" method="post" class="inline-block ms-3" onsubmit="return confirm('Delete this item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $items->withQueryString()->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>


