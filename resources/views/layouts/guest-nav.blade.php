<nav x-data="{ open: false }" class="sticky top-0 z-40 transition bg-gradient-to-r from-[var(--brand-primary)] to-[var(--brand-primary-2)]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-[var(--brand-text)]" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="url('/')" :active="request()->is('/')" variant="brand">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/products')" :active="request()->is('products*')" variant="brand">
                        {{ __('Products') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/compare')" :active="request()->is('compare')" variant="brand">
                        {{ __('Compare') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/about')" :active="request()->is('about')" variant="brand">
                        {{ __('About Us') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/contact')" :active="request()->is('contact')" variant="brand">
                        {{ __('Contact Us') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/faq')" :active="request()->is('faq')" variant="brand">
                        {{ __('FAQ') }}
                    </x-nav-link>
                    @guest
                        <x-nav-link :href="route('oauth.redirect', ['provider' => 'google'])" :active="request()->is('login')" variant="brand">
                            {{ __('Google Sign in') }}
                        </x-nav-link>
                    @endguest
                    @auth
                        <x-nav-link :href="route('logout')" :active="false" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" variant="brand">
                            {{ __('Logout') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-[var(--brand-text)] hover:text-black hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-black" aria-controls="mobile-menu" :aria-expanded="open.toString()">
                    <span class="sr-only">Open main menu</span>
                    <svg x-show="!open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div class="sm:hidden" id="mobile-menu" x-show="open" x-cloak>
        <div class="pt-2 pb-3 space-y-1 bg-gradient-to-b from-[var(--brand-primary)] to-[var(--brand-primary-2)] text-white">
            <a href="{{ url('/') }}" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10 {{ request()->is('/') ? 'bg-white/10 text-white' : '' }}">Home</a>
            <a href="{{ url('/products') }}" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10 {{ request()->is('products*') ? 'bg-white/10 text-white' : '' }}">Products</a>
            <a href="{{ url('/compare') }}" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10 {{ request()->is('compare') ? 'bg-white/10 text-white' : '' }}">Compare</a>
            <a href="{{ url('/about') }}" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10 {{ request()->is('about') ? 'bg-white/10 text-white' : '' }}">About Us</a>
            <a href="{{ url('/contact') }}" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10 {{ request()->is('contact') ? 'bg-white/10 text-white' : '' }}">Contact Us</a>
            <a href="{{ url('/faq') }}" class="block ps-3 pe-4 py-2 text-base font-medium hover:bg-white/10 {{ request()->is('faq') ? 'bg-white/10 text-white' : '' }}">FAQ</a>
            @auth
            <x-nav-link :href="route('logout')" :active="false" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block ps-3 pe-4 py-2 text-base font-medium text-white/90 hover:text-white">
                {{ __('Logout') }}
            </x-nav-link>
            @endauth
        </div>
    </div>
</nav>
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>
