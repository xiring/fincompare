<footer class="bg-gray-50 border-t">
    <div class="h-1 bg-gradient-to-r from-indigo-600 via-blue-500 to-amber-400"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-lg bg-indigo-600"></div>
                    <div class="text-lg font-semibold text-gray-900">{{ config('app.name', 'FinCompare') }}</div>
                </div>
                <p class="mt-3 text-sm text-gray-600">Compare financial products side-by-side and apply with confidence.</p>
                <div class="mt-4 flex items-center gap-3 text-gray-500">
                    <a href="#" aria-label="Twitter" class="hover:text-gray-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M22 5.8c-.7.3-1.5.5-2.2.6.8-.5 1.4-1.2 1.7-2.1-.8.5-1.7.9-2.6 1.1-.7-.8-1.7-1.3-2.8-1.3-2.1 0-3.8 1.7-3.8 3.9 0 .3 0 .7.1 1C8.3 9 5.4 7.5 3.4 5.1c-.4.7-.6 1.4-.6 2.2 0 1.4.7 2.7 1.8 3.4-.6 0-1.2-.2-1.7-.5v.1c0 1.9 1.3 3.4 3.1 3.8-.3.1-.6.1-.9.1-.2 0-.4 0-.6-.1.4 1.4 1.8 2.5 3.4 2.5-1.2 1-2.7 1.6-4.4 1.6-.3 0-.6 0-.9-.1 1.6 1.1 3.5 1.7 5.6 1.7 6.7 0 10.4-5.7 10.4-10.7v-.5c.7-.5 1.4-1.2 1.9-2z"/></svg>
                    </a>
                    <a href="#" aria-label="LinkedIn" class="hover:text-gray-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM0 8h5v16H0V8zm7.5 0h4.8v2.2h.1c.7-1.2 2.3-2.5 4.7-2.5 5 0 5.9 3.3 5.9 7.6V24h-5V16c0-1.9 0-4.4-2.7-4.4-2.7 0-3.1 2.1-3.1 4.2V24h-5V8z"/></svg>
                    </a>
                    <a href="#" aria-label="YouTube" class="hover:text-gray-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2c-.3-1.2-1.3-2.1-2.5-2.4C19 3.3 12 3.3 12 3.3s-7 0-9 .5C1.8 4.1.9 5 .6 6.2.1 8 .1 12 .1 12s0 4 .5 5.8c.3 1.2 1.3 2.1 2.5 2.4 2 .5 9 .5 9 .5s7 0 9-.5c1.2-.3 2.2-1.2 2.5-2.4.5-1.8.5-5.8.5-5.8s0-4-.5-5.8zM9.8 15.5V8.5l6.2 3.5-6.2 3.5z"/></svg>
                    </a>
                </div>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-900">Products</div>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    <li><a href="{{ url('/products') }}" class="hover:text-gray-800">Browse products</a></li>
                    <li><a href="{{ route('compare') }}" class="hover:text-gray-800">Compare</a></li>
                    <li><a href="{{ route('leads.create') }}" class="hover:text-gray-800">Send inquiry</a></li>
                </ul>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-900">Company</div>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    <li><a href="{{ route('about') }}" class="hover:text-gray-800">About</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-gray-800">Contact</a></li>
                </ul>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-900">Resources</div>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    <li><a href="{{ route('faq') }}" class="hover:text-gray-800">Help center</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-gray-800">Blog</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-8 pt-6 border-t text-xs text-gray-600 flex flex-col md:flex-row items-center justify-between gap-2">
            <div>
                &copy; {{ date('Y') }} {{ config('app.name', 'FinCompare') }}. All rights reserved.
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('privacy') }}" class="hover:text-gray-800">Privacy</a>
                <a href="{{ route('terms') }}" class="hover:text-gray-800">Terms</a>
            </div>
        </div>
    </div>
</footer>


