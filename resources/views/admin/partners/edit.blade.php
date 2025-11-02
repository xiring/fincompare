<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Partner') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form action="{{ route('admin.partners.update', $partner) }}" method="post" class="max-w-xl space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" value="{{ old('name', $partner->name) }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="slug" value="Slug (optional)" />
                        <x-text-input id="slug" name="slug" value="{{ old('slug', $partner->slug) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="logo_path" value="Logo Path" />
                        <x-text-input id="logo_path" name="logo_path" value="{{ old('logo_path', $partner->logo_path) }}" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <x-input-label for="website_url" value="Website URL" />
                        <x-text-input id="website_url" name="website_url" value="{{ old('website_url', $partner->website_url) }}" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <x-input-label for="contact_email" value="Contact Email" />
                        <x-text-input id="contact_email" name="contact_email" value="{{ old('contact_email', $partner->contact_email) }}" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <x-input-label for="contact_phone" value="Contact Phone" />
                        <x-text-input id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $partner->contact_phone) }}" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <x-input-label for="status" value="Status" />
                        <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="active" {{ old('status', $partner->status)==='active'?'selected':'' }}>active</option>
                            <option value="inactive" {{ old('status', $partner->status)==='inactive'?'selected':'' }}>inactive</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.partners.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</a>
                        <x-primary-button>Save</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


