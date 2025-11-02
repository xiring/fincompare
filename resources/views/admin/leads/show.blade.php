<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Lead #') }}{{ $lead->id }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600">Name</div>
                        <div class="font-medium">{{ $lead->full_name }}</div>
                        <div class="text-sm text-gray-600 mt-2">Email</div>
                        <div class="font-medium">{{ $lead->email }}</div>
                        <div class="text-sm text-gray-600 mt-2">Mobile</div>
                        <div class="font-medium">{{ $lead->mobile_number }}</div>
                        <div class="text-sm text-gray-600 mt-2">Status</div>
                        <div class="font-medium">{{ $lead->status }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Category</div>
                        <div class="font-medium">{{ optional($lead->productCategory)->name }}</div>
                        <div class="text-sm text-gray-600 mt-2">Product</div>
                        <div class="font-medium">{{ optional($lead->product)->name }}</div>
                        <div class="text-sm text-gray-600 mt-2">Source</div>
                        <div class="font-medium">{{ $lead->source }}</div>
                        <div class="text-sm text-gray-600 mt-2">Created</div>
                        <div class="font-medium">{{ $lead->created_at }}</div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form method="post" action="{{ route('admin.leads.update', $lead) }}" class="max-w-xl space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="status" value="Status" />
                        <x-text-input id="status" name="status" value="{{ old('status', $lead->status) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="message" value="Message" />
                        <textarea id="message" name="message" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('message', $lead->message) }}</textarea>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.leads.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md">Back</a>
                        <x-primary-button>Update</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


