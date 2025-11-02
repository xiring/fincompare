<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="url('/')" :active="request()->is('/')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/products')" :active="request()->is('products*')">
                        {{ __('Products') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/compare')" :active="request()->is('compare')">
                        {{ __('Compare') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/partners')" :active="request()->is('partners')">
                        {{ __('Partners') }}
                    </x-nav-link>
                </div>
            </div>
        </div>
    </div>
</nav>
