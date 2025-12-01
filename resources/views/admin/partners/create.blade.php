<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Create Partner') }}</h2>
                <p class="mt-1 text-sm text-gray-600">Add a new partner organization</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <form action="{{ route('admin.partners.store') }}" method="post" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" value="{{ old('name') }}" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="slug" value="Slug (optional)" />
                    <x-text-input id="slug" name="slug" value="{{ old('slug') }}" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="logo_path" value="Logo Path" />
                    <x-text-input id="logo_path" name="logo_path" value="{{ old('logo_path') }}" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-input-label for="website_url" value="Website URL" />
                    <x-text-input id="website_url" name="website_url" type="url" value="{{ old('website_url') }}" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-input-label for="contact_email" value="Contact Email" />
                    <x-text-input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email') }}" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-input-label for="contact_phone" value="Contact Phone" />
                    <x-text-input id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        <option value="active" {{ old('status')==='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status')==='inactive'?'selected':'' }}>Inactive</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.partners.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <x-primary-button>Save Partner</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


