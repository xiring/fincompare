<x-guest-layout>
    <!-- Header band -->
    <section class="relative overflow-hidden bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white animate-fade-in">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-[color:var(--brand-primary)]/20 blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h1 class="text-3xl font-extrabold tracking-tight">Send Inquiry</h1>
            <p class="mt-1 text-white/90">We'll connect you with our partner for the selected product.</p>
        </div>
    </section>

    <div x-data="leadForm()" class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10 animate-fade-in-up">
        <div class="bg-white border rounded-2xl p-6">
            <h2 class="text-xl font-semibold mb-4">Your details</h2>

        <div class="mb-6">
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-[color:var(--brand-primary)] h-2 rounded-full" :style="`width: ${progress}%`"></div>
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
                        <input x-model="form.full_name" @blur="touched.full_name=true" name="full_name" value="{{ old('full_name') }}" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]" required>
                        <p class="text-sm text-red-600 mt-1" x-show="touched.full_name && !form.full_name">Full name is required.</p>
                        @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input x-model="form.email" @blur="touched.email=true" type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]" required>
                            <p class="text-sm text-red-600 mt-1" x-show="touched.email && (!form.email || !$el.checkValidity())">Enter a valid email address.</p>
                            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mobile Number</label>
                            <input x-model="form.phone" @blur="touched.phone=true" type="tel" name="phone" value="{{ old('phone') }}" pattern="[0-9+\-()\s]{7,}" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]" required>
                            <p class="text-sm text-red-600 mt-1" x-show="touched.phone && (!form.phone || !$el.checkValidity())">Enter a valid phone number.</p>
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
                            <input x-model="form.city" name="city" value="{{ old('city') }}" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]">
                            @error('city') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product</label>
                            <input value="{{ $product->name ?? old('product_name') }}" class="mt-1 w-full rounded-lg border-gray-300 bg-gray-100" disabled>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Brief Message (optional)</label>
                        <textarea x-model="form.message" name="message" rows="4" class="mt-1 w-full rounded-lg border-gray-300 focus:border-[color:var(--brand-primary)] focus:ring-[color:var(--brand-primary)]">{{ old('message') }}</textarea>
                        @error('message') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <label class="inline-flex items-start gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="consent" value="1" required x-model="form.consent" class="mt-0.5 rounded border-gray-300 accent-[color:var(--brand-primary)]">
                        <span>I agree to be contacted by FinCompare and its partners. See our <a href="{{ route('privacy') }}" class="text-[color:var(--brand-primary)] underline">Privacy Policy</a>.</span>
                    </label>
                    @error('consent') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
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
                <button type="button" @click="prev" x-show="step>1" class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 font-medium hover:bg-gray-50 transition-colors">Back</button>
                <div class="ml-auto flex items-center gap-3">
                    <button type="button" @click="next" x-show="step<3" :disabled="!canNext" :class="canNext ? 'bg-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)] text-white' : 'bg-gray-200 text-gray-500 cursor-not-allowed'" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg font-semibold transition-colors">Next</button>
                    <button type="submit" x-show="step===3" :disabled="!form.consent || submitting" class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg bg-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary-2)] text-white font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
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
    </div>
</x-guest-layout>

<script>
    /**
     * Handle Lead form.
     * @return mixed
     */
    function leadForm() {
        return {
            step: 1,
            submitting: false,
            form: { full_name: '', email: '', phone: '', city: '', message: '', consent: false },
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


