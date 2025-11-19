<footer class="bg-[var(--brand-bg)] border-t">
    <div class="h-1 bg-gradient-to-r from-[var(--brand-primary)] to-[var(--brand-primary-2)]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-lg bg-gradient-to-r from-[var(--brand-primary)] to-[var(--brand-primary-2)]">
                        <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />
                    </div>
                    <div class="text-lg font-semibold text-gray-900">{{ $siteSettings->site_name }}</div>
                </div>
                <p class="mt-3 text-sm text-gray-600">{{ $siteSettings->site_slogon }}</p>
                <div class="mt-4 flex items-center gap-3 text-gray-500">
                    @if(!empty($siteSettings->twitter_url))
                        <a href="{{ $siteSettings->twitter_url }}" target="_blank" aria-label="Twitter" class="inline-flex h-8 w-8 items-center justify-center rounded-md border text-[color:var(--brand-primary)] border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary)] hover:text-white transition">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M22 5.8c-.7.3-1.5.5-2.2.6.8-.5 1.4-1.2 1.7-2.1-.8.5-1.7.9-2.6 1.1-.7-.8-1.7-1.3-2.8-1.3-2.1 0-3.8 1.7-3.8 3.9 0 .3 0 .7.1 1C8.3 9 5.4 7.5 3.4 5.1c-.4.7-.6 1.4-.6 2.2 0 1.4.7 2.7 1.8 3.4-.6 0-1.2-.2-1.7-.5v.1c0 1.9 1.3 3.4 3.1 3.8-.3.1-.6.1-.9.1-.2 0-.4 0-.6-.1.4 1.4 1.8 2.5 3.4 2.5-1.2 1-2.7 1.6-4.4 1.6-.3 0-.6 0-.9-.1 1.6 1.1 3.5 1.7 5.6 1.7 6.7 0 10.4-5.7 10.4-10.7v-.5c.7-.5 1.4-1.2 1.9-2z"/></svg>
                        </a>
                    @endif
                    @if(!empty($siteSettings->instgram_url))
                        <a href="{{ $siteSettings->instgram_url }}" target="_blank" aria-label="Instagram" class="inline-flex h-8 w-8 items-center justify-center rounded-md border text-[color:var(--brand-primary)] border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary)] hover:text-white transition">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75 20.5h8.5A4.25 4.25 0 0 0 20.5 16.25v-8.5A4.25 4.25 0 0 0 16.25 3.5zm4.25 3.25A5.25 5.25 0 1 1 6.75 12 5.26 5.26 0 0 1 12 6.75zm0 1.5a3.75 3.75 0 1 0 3.75 3.75A3.75 3.75 0 0 0 12 8.25zm5.13-.88a1.13 1.13 0 1 1-1.13 1.13 1.13 1.13 0 0 1 1.13-1.13z"/>
                            </svg>
                        </a>
                    @endif
                    @if(!empty($siteSettings->facebook_url))
                    <a href="{{ $siteSettings->facebook_url }}" target="_blank" aria-label="Facebook" class="inline-flex h-8 w-8 items-center justify-center rounded-md border text-[color:var(--brand-primary)] border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary)] hover:text-white transition">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.675 0h-21.35C.597 0 0 .598 0 1.333v21.334C0 23.402.597 24 1.325 24h11.495v-9.294H9.691v-3.622h3.129V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.797.143v3.24l-1.918.001c-1.504 0-1.797.715-1.797 1.763v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.403 24 24 23.402 24 22.667V1.333C24 .598 23.403 0 22.675 0z"/>
                        </svg>
                    </a>
                    @endif
                    @if(!empty($siteSettings->email_address))
                        <a href="mailto:{{ $siteSettings->email_address }}" target="_blank" aria-label="Email" class="inline-flex h-8 w-8 items-center justify-center rounded-md border text-[color:var(--brand-primary)] border-[color:var(--brand-primary)] hover:bg-[color:var(--brand-primary)] hover:text-white transition">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M2.01 4.5c-.56 0-1.01.45-1.01 1v13c0 .55.45 1 1.01 1h19.98c.56 0 1.01-.45 1.01-1v-13c0-.55-.45-1-1.01-1h-19.98zm17.99 2-7.99 5.01-7.99-5.01h15.98zm1 11.5h-17.98v-9.72l8.99 5.64 8.99-5.64v9.72z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-900">Products</div>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    <li><a href="{{ url('/products') }}" class="hover:text-[color:var(--brand-primary)]">Browse products</a></li>
                    <li><a href="{{ route('compare') }}" class="hover:text-[color:var(--brand-primary)]">Compare</a></li>
                    <li><a href="{{ route('leads.create') }}" class="hover:text-[color:var(--brand-primary)]">Send inquiry</a></li>
                </ul>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-900">Company</div>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    <li><a href="{{ route('about') }}" class="hover:text-[color:var(--brand-primary)]">About</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[color:var(--brand-primary)]">Contact</a></li>
                </ul>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-900">Resources</div>
                <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    <li><a href="{{ route('faq') }}" class="hover:text-[color:var(--brand-primary)]">Help center</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-[color:var(--brand-primary)]">Blog</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-8 pt-6 border-t text-xs text-gray-600 flex flex-col md:flex-row items-center justify-between gap-2">
            <div>
                &copy; {{ date('Y') }} {{ $siteSettings->site_name }}. All rights reserved.
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('privacy') }}" class="hover:text-[color:var(--brand-primary)]">Privacy</a>
                <a href="{{ route('terms') }}" class="hover:text-[color:var(--brand-primary)]">Terms</a>
            </div>
        </div>
    </div>
</footer>
