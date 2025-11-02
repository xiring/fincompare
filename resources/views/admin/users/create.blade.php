<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Create User') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form action="{{ route('admin.users.store') }}" method="post" class="max-w-xl space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" value="{{ old('name') }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" value="{{ old('email') }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" value="Password" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Confirm Password" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                    </div>

                    <div>
                        <x-input-label for="roles" value="Roles" />
                        <select id="roles" name="roles[]" class="mt-1 block w-full border-gray-300 rounded-md" multiple>
                            @foreach($roles as $r)
                                <option value="{{ $r->name }}" {{ (collect(old('roles'))->contains($r->name))?'selected':'' }}>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</a>
                        <x-primary-button>Save</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


