<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Lead #') }}{{ $lead->id }}</h2>
                <p class="mt-1 text-sm text-gray-600">View and update lead information</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Lead Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Name</div>
                        <div class="text-sm font-medium text-gray-900">{{ $lead->full_name }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email</div>
                        <div class="text-sm text-gray-900">{{ $lead->email }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Mobile</div>
                        <div class="text-sm text-gray-900">{{ $lead->mobile_number }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status</div>
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
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Category</div>
                        <div class="text-sm text-gray-900">{{ optional($lead->productCategory)->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Product</div>
                        <div class="text-sm text-gray-900">{{ optional($lead->product)->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Source</div>
                        <div class="text-sm text-gray-900">{{ $lead->source ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Created</div>
                        <div class="text-sm text-gray-900">{{ $lead->created_at->format('F j, Y g:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Lead</h3>
            <form method="post" action="{{ route('admin.leads.update', $lead) }}" class="max-w-2xl space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        @foreach(['new','in_progress','closed','rejected','won','lost'] as $s)
                            <option value="{{ $s }}" {{ old('status', $lead->status) === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="message" value="Message" />
                    <textarea id="message" name="message" rows="4" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">{{ old('message', $lead->message) }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        Back
                    </a>
                    <x-primary-button>Update Lead</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


