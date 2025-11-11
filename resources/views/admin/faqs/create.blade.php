<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('New FAQ') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form action="{{ route('admin.faqs.store') }}" method="post" class="space-y-6">
                    @csrf
                    <div>
                        <x-input-label for="question" value="Question" />
                        <x-text-input id="question" name="question" value="{{ old('question') }}" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('question')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="answer" value="Answer" />
                        <textarea id="answer" name="answer" class="mt-1 block w-full border-gray-300 rounded-md" rows="5" required>{{ old('answer') }}</textarea>
                        <x-input-error :messages="$errors->get('answer')" class="mt-2" />
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.faqs.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</a>
                        <x-primary-button>Save</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


