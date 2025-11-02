<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Activity Log') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form method="get" class="flex flex-wrap items-end gap-2">
                    <div>
                        <x-input-label for="log_name" value="Log Name" />
                        <x-text-input id="log_name" name="log_name" value="{{ request('log_name') }}" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="subject_type" value="Subject Type" />
                        <x-text-input id="subject_type" name="subject_type" value="{{ request('subject_type') }}" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="causer_id" value="Causer ID" />
                        <x-text-input id="causer_id" name="causer_id" value="{{ request('causer_id') }}" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="per_page" value="Per Page" />
                        <select id="per_page" name="per_page" class="mt-1 border-gray-300 rounded-md">
                            @foreach([10,20,50,100] as $pp)
                                <option value="{{ $pp }}" {{ (int)request('per_page',20)===$pp?'selected':'' }}>{{ $pp }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-primary-button>Filter</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-3 py-2 text-left">ID</th>
                                <th class="px-3 py-2 text-left">Log</th>
                                <th class="px-3 py-2 text-left">Event</th>
                                <th class="px-3 py-2 text-left">Subject</th>
                                <th class="px-3 py-2 text-left">Causer</th>
                                <th class="px-3 py-2 text-left">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($items as $a)
                                <tr>
                                    <td class="px-3 py-2">{{ $a->id }}</td>
                                    <td class="px-3 py-2">{{ $a->log_name }}</td>
                                    <td class="px-3 py-2">{{ $a->event }}</td>
                                    <td class="px-3 py-2">{{ str($a->subject_type)->classBasename() }} #{{ $a->subject_id }}</td>
                                    <td class="px-3 py-2">{{ $a->causer_id }}</td>
                                    <td class="px-3 py-2">{{ $a->created_at }}</td>
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


