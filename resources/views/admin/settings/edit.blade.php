<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Site Settings') }}</h2>
                <p class="mt-1 text-sm text-gray-600">Manage site configuration and preferences</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="site_name" value="Site Name" />
                        <x-text-input id="site_name" name="site_name" value="{{ old('site_name', $settings->site_name) }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('site_name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="site_slogon" value="Site Slogan" />
                        <x-text-input id="site_slogon" name="site_slogon" value="{{ old('site_slogon', $settings->site_slogon) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('site_slogon')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="favicon" value="Favicon" />
                        <input id="favicon" name="favicon" type="file" accept="image/*" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" />
                        <x-input-error :messages="$errors->get('favicon')" class="mt-2" />
                        @if ($settings->favicon)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings->favicon) }}" alt="Favicon" class="h-8 w-8 object-contain">
                            </div>
                        @endif
                    </div>

                    <div>
                        <x-input-label for="logo" value="Logo" />
                        <input id="logo" name="logo" type="file" accept="image/*" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" />
                        <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                        @if ($settings->logo)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" class="h-12 object-contain">
                            </div>
                        @endif
                    </div>

                    <div>
                        <x-input-label for="seo_titl" value="SEO Title" />
                        <x-text-input id="seo_titl" name="seo_titl" value="{{ old('seo_titl', $settings->seo_titl) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('seo_titl')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="seo_keyword" value="SEO Keywords" />
                        <x-text-input id="seo_keyword" name="seo_keyword" value="{{ old('seo_keyword', $settings->seo_keyword) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('seo_keyword')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="seo_description" value="SEO Description" />
                        <textarea id="seo_description" name="seo_description" rows="3" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">{{ old('seo_description', $settings->seo_description) }}</textarea>
                        <x-input-error :messages="$errors->get('seo_description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email_address" value="Email Address" />
                        <x-text-input id="email_address" name="email_address" type="email" value="{{ old('email_address', $settings->email_address) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('email_address')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="contact_number" value="Contact Number" />
                        <x-text-input id="contact_number" name="contact_number" value="{{ old('contact_number', $settings->contact_number) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="address" value="Address" />
                        <textarea id="address" name="address" rows="2" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">{{ old('address', $settings->address) }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="map_url" value="Map URL" />
                        <x-text-input id="map_url" name="map_url" type="url" value="{{ old('map_url', $settings->map_url) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('map_url')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="facebook_url" value="Facebook URL" />
                        <x-text-input id="facebook_url" name="facebook_url" type="url" value="{{ old('facebook_url', $settings->facebook_url) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('facebook_url')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="instgram_url" value="Instagram URL" />
                        <x-text-input id="instgram_url" name="instgram_url" type="url" value="{{ old('instgram_url', $settings->instgram_url) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('instgram_url')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="twitter_url" value="Twitter URL" />
                        <x-text-input id="twitter_url" name="twitter_url" type="url" value="{{ old('twitter_url', $settings->twitter_url) }}" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('twitter_url')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
                    <x-primary-button>{{ __('Save Settings') }}</x-primary-button>
                    @if (session('status') === 'settings-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-green-600 font-medium"
                        >{{ __('Saved.') }}</p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


