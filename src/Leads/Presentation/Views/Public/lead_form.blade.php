<x-guest-layout>
    <div x-data="leadForm()" class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-bold mb-6">Send Inquiry</h1>

        <div class="mb-6">
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-indigo-600 h-2 rounded-full" :style="`width: ${progress}%`"></div>
            </div>
            <div class="mt-2 text-sm text-gray-600">Step <span x-text="step"></span> of 3</div>
        </div>

        <form method="post" action="{{ route('leads.store') }}" @submit="submitting=true" class="space-y-5">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id ?? '' }}">

            <template x-if="step===1">
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input x-model="form.full_name" name="full_name" value="{{ old('full_name') }}" class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input x-model="form.email" type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mobile Number</label>
                            <input x-model="form.phone" type="tel" name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="step===2">
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">City</label>
                            <input x-model="form.city" name="city" value="{{ old('city') }}" class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('city') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product</label>
                            <input value="{{ $product->name ?? old('product_name') }}" class="mt-1 w-full rounded-md border-gray-300 bg-gray-100" disabled>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Brief Message (optional)</label>
                        <textarea x-model="form.message" name="message" rows="4" class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('message') }}</textarea>
                        @error('message') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </template>

            <template x-if="step===3">
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold">Review & Submit</h2>
                    <dl class="bg-white border rounded-md divide-y">
                        <div class="flex items-center justify-between px-4 py-3">
                            <dt class="text-sm text-gray-600">Full Name</dt>
                            <dd class="text-sm font-medium" x-text="form.full_name"></dd>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3">
                            <dt class="text-sm text-gray-600">Email</dt>
                            <dd class="text-sm font-medium" x-text="form.email"></dd>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3">
                            <dt class="text-sm text-gray-600">Phone</dt>
                            <dd class="text-sm font-medium" x-text="form.phone"></dd>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3">
                            <dt class="text-sm text-gray-600">City</dt>
                            <dd class="text-sm font-medium" x-text="form.city || '—'"></dd>
                        </div>
                        <div class="px-4 py-3">
                            <dt class="text-sm text-gray-600 mb-1">Message</dt>
                            <dd class="text-sm font-medium whitespace-pre-wrap" x-text="form.message || '—'"></dd>
                        </div>
                    </dl>
                </div>
            </template>

            <div class="flex items-center justify-between pt-2">
                <button type="button" @click="prev" x-show="step>1" class="px-4 py-2 rounded-md border">Back</button>
                <div class="ml-auto flex items-center gap-3">
                    <button type="button" @click="next" x-show="step<3" :disabled="!canNext" :class="canNext ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500 cursor-not-allowed'" class="px-5 py-2 rounded-md font-semibold">Next</button>
                    <button type="submit" x-show="step===3" class="inline-flex items-center px-6 py-3 rounded-md bg-indigo-600 text-white font-semibold" :disabled="submitting">
                        <svg x-show="submitting" class="-ml-1 mr-2 h-5 w-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>

<script>
    function leadForm() {
        return {
            step: 1,
            submitting: false,
            form: { full_name: '', email: '', phone: '', city: '', message: '' },
            get progress() { return this.step * 33.33; },
            get canNext() {
                if (this.step === 1) return this.form.full_name && this.form.email && this.form.phone;
                if (this.step === 2) return true;
                return false;
            },
            next() { if (this.step < 3 && this.canNext) this.step++; },
            prev() { if (this.step > 1) this.step--; }
        }
    }
</script>


