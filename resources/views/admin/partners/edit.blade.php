<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Edit Partner') }}</h2>
                <p class="mt-1 text-sm text-gray-600">Update partner information</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <form action="{{ route('admin.partners.update', $partner) }}" method="post" class="space-y-6" enctype="multipart/form-data">
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
                    <x-input-label for="logo" value="Logo (optional)" />
                    <input id="logo" name="logo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" />
                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF, WEBP up to 2MB</p>
                    <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                    @if($partner->logo_path)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 mb-2">Current Logo:</p>
                            <img src="{{ asset('storage/' . $partner->logo_path) }}" alt="{{ $partner->name }}" class="w-24 h-24 object-contain rounded-lg border border-gray-200 bg-gray-50 p-2">
                        </div>
                    @endif
                </div>

                <div>
                    <x-input-label for="website_url" value="Website URL" />
                    <x-text-input id="website_url" name="website_url" type="url" value="{{ old('website_url', $partner->website_url) }}" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-input-label for="contact_email" value="Contact Email" />
                    <x-text-input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $partner->contact_email) }}" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-input-label for="contact_phone" value="Contact Phone" />
                    <x-text-input id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $partner->contact_phone) }}" class="mt-1 block w-full" />
                </div>

                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        <option value="active" {{ old('status', $partner->status)==='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status', $partner->status)==='inactive'?'selected':'' }}>Inactive</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.partners.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <x-primary-button>Update Partner</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


