<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Activity Log') }}</h2>
                <p class="mt-1 text-sm text-gray-600">View system activity and audit trail</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <form method="get" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <x-input-label for="log_name" value="Log Name" />
                    <x-text-input id="log_name" name="log_name" value="{{ request('log_name') }}" class="mt-1 w-full" />
                </div>
                <div class="flex-1 min-w-[200px]">
                    <x-input-label for="subject_type" value="Subject Type" />
                    <x-text-input id="subject_type" name="subject_type" value="{{ request('subject_type') }}" class="mt-1 w-full" />
                </div>
                <div class="flex-1 min-w-[150px]">
                    <x-input-label for="causer_id" value="Causer ID" />
                    <x-text-input id="causer_id" name="causer_id" value="{{ request('causer_id') }}" class="mt-1 w-full" />
                </div>
                <div class="min-w-[150px]">
                    <x-input-label for="per_page" value="Per Page" />
                    <select id="per_page" name="per_page" class="mt-1 border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm w-full">
                        @foreach([10,20,50,100] as $pp)
                            <option value="{{ $pp }}" {{ (int)request('per_page',20)===$pp?'selected':'' }}>{{ $pp }} per page</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-primary-button>Filter</x-primary-button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-soft border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Log</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Causer</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items as $a)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $a->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $a->log_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $a->event }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ str($a->subject_type)->classBasename() }} #{{ $a->subject_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $a->causer_id ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $a->created_at->format('Y-m-d H:i') }}</td>
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


