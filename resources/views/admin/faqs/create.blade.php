<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('New FAQ') }}</h2>
                <p class="mt-1 text-sm text-gray-600">Add a new frequently asked question</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-lg shadow-soft border border-gray-200 p-6">
            <form action="{{ route('admin.faqs.store') }}" method="post" class="space-y-6">
                @csrf
                <div>
                    <x-input-label for="question" value="Question" />
                    <x-text-input id="question" name="question" value="{{ old('question') }}" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('question')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="answer" value="Answer" />
                    <textarea id="answer" name="answer" rows="6" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" required>{{ old('answer') }}</textarea>
                    <x-input-error :messages="$errors->get('answer')" class="mt-2" />
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.faqs.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <x-primary-button>Save FAQ</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


