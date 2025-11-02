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
                    <li><a href="#" class="hover:text-gray-800">About</a></li>
                    <li><a href="#" class="hover:text-gray-800">Careers</a></li>
                    <li><a href="#" class="hover:text-gray-800">Contact</a></li>
                </ul>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-900">Resources</div>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-gray-800">Help center</a></li>
                    <li><a href="#" class="hover:text-gray-800">Blog</a></li>
                    <li><a href="#" class="hover:text-gray-800">Guides</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-8 pt-6 border-t text-xs text-gray-600 flex flex-col md:flex-row items-center justify-between gap-2">
            <div>
                &copy; {{ date('Y') }} {{ config('app.name', 'FinCompare') }}. All rights reserved.
            </div>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-gray-800">Privacy</a>
                <a href="#" class="hover:text-gray-800">Terms</a>
                <a href="#" class="hover:text-gray-800">Cookies</a>
            </div>
        </div>
    </div>
</footer>


