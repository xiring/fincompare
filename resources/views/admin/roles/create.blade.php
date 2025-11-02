<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Create Role') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form action="{{ route('admin.roles.store') }}" method="post" class="max-w-xl space-y-6">
                    @csrf
                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" value="{{ old('name') }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="permissions" value="Permissions" />
                        <select id="permissions" name="permissions[]" class="mt-1 block w-full border-gray-300 rounded-md" multiple>
                            @foreach($permissions as $p)
                                <option value="{{ $p->name }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</a>
                        <x-primary-button>Save</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


