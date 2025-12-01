<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Edit Role') }}</h2>
                <p class="mt-1 text-sm text-gray-600">Update user role and permissions</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <form action="{{ route('admin.roles.update', $role) }}" method="post" class="space-y-6">
                @csrf
                @method('PATCH')
                <div>
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" value="{{ old('name', $role->name) }}" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="permissions" value="Permissions" />
                    @php $selected = collect(old('permissions', $role->permissions->pluck('name')->all())); @endphp
                    <select id="permissions" name="permissions[]" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" multiple size="8">
                        @foreach($permissions as $p)
                            <option value="{{ $p->name }}" {{ $selected->contains($p->name)?'selected':'' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple permissions</p>
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <x-primary-button>Update Role</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


