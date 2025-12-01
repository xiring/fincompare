<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('storage/' . $siteSettings->favicon) }}">

        <title>{{ $siteSettings->site_name }} - Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div x-data="{ sidebarOpen: false, sidebarCollapsed: false }" class="min-h-screen">
            <x-toast />
            @stack('styles')

            <!-- Mobile overlay -->
            <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-30 bg-black/50 md:hidden transition-opacity" @click="sidebarOpen=false"></div>

            <!-- Sidebar -->
            <aside
                x-bind:class="sidebarCollapsed ? 'md:w-20' : 'md:w-64'"
                class="fixed inset-y-0 left-0 z-40 w-64 md:translate-x-0 -translate-x-full md:fixed md:flex md:flex-col bg-white border-r border-gray-200/80 shadow-sm overflow-y-auto transition-all duration-300"
                :class="{ 'translate-x-0': sidebarOpen }"
            >
                <div class="h-16 flex items-center px-4 border-b border-gray-200/80">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <x-application-logo class="h-8 w-8 text-primary-600" />
                        <span x-show="!sidebarCollapsed" class="font-bold text-lg text-gray-900">Admin</span>
                    </a>
                    <!-- Desktop collapse toggle -->
                    <button @click="sidebarCollapsed=!sidebarCollapsed"
                            class="ms-auto hidden md:inline-flex items-center justify-center rounded-lg p-2 hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors"
                            aria-label="Toggle sidebar">
                        <svg class="h-5 w-5 transition-transform duration-300"
                             :class="sidebarCollapsed ? 'rotate-180' : ''"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <!-- Mobile close button -->
                    <button class="ms-auto md:hidden rounded-lg p-2 hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors" @click="sidebarOpen=false">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="p-4 space-y-1">
                    <a href="{{ route('dashboard') }}"
                       title="Dashboard"
                       :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-10 h-10 rounded-lg mx-auto' : 'gap-3 px-3 py-2.5 rounded-lg'"
                       class="flex items-center text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.partners.index') }}"
                       title="Partners"
                       :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-10 h-10 rounded-lg mx-auto' : 'gap-3 px-3 py-2.5 rounded-lg'"
                       class="flex items-center text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.partners.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 10-8 0v4m-2 4h12a2 2 0 012 2v3H2v-3a2 2 0 012-2z"/></svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Partners</span>
                    </a>

                    <!-- Catalog -->
                    <div x-data="{ open: {{ request()->routeIs('admin.product-categories.*') || request()->routeIs('admin.products.*') || request()->routeIs('admin.attributes.*') ? 'true' : 'false' }} }" class="space-y-1">
                        <button @click="open=!open" title="Catalog" class="w-full flex items-center text-sm font-medium transition-all duration-200"
                                :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-10 h-10 rounded-lg mx-auto' : 'gap-3 px-3 py-2.5 rounded-lg'"
                                :class="open ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'">
                            <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span x-show="!sidebarCollapsed" class="flex-1 text-left truncate">Catalog</span>
                            <svg x-show="!sidebarCollapsed" class="h-4 w-4 ms-auto flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.127l3.71-3.896a.75.75 0 111.08 1.04l-4.24 4.46a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                        </button>
                        <div x-show="open && !sidebarCollapsed" x-collapse x-cloak class="ms-8 space-y-1 border-l-2 border-gray-100 pl-4">
                            <a href="{{ route('admin.product-categories.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.product-categories.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">Categories</a>
                            <a href="{{ route('admin.products.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">Products</a>
                            <a href="{{ route('admin.attributes.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.attributes.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">Attributes</a>
                        </div>
                    </div>

                    <a href="{{ route('admin.leads.index') }}"
                       title="Leads"
                       :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-10 h-10 rounded-lg mx-auto' : 'gap-3 px-3 py-2.5 rounded-lg'"
                       class="flex items-center text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.leads.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z"/></svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Leads</span>
                    </a>

                    <a href="{{ route('admin.activity.index') }}"
                       title="Activity"
                       :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-10 h-10 rounded-lg mx-auto' : 'gap-3 px-3 py-2.5 rounded-lg'"
                       class="flex items-center text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.activity.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 12h2m-1-9a9 9 0 100 18 9 9 0 000-18z"/></svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Activity</span>
                    </a>

                    @if(auth()->user()?->hasRole('admin'))
                        <div x-data="{ open: {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'true' : 'false' }} }" class="space-y-1">
                            <button @click="open=!open" title="Access" class="w-full flex items-center text-sm font-medium transition-all duration-200"
                                    :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-10 h-10 rounded-lg mx-auto' : 'gap-3 px-3 py-2.5 rounded-lg'"
                                    :class="open ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'">
                                <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span x-show="!sidebarCollapsed" class="flex-1 text-left truncate">Access</span>
                                <svg x-show="!sidebarCollapsed" class="h-4 w-4 ms-auto flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.127l3.71-3.896a.75.75 0 111.08 1.04l-4.24 4.46a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                            </button>
                            <div x-show="open && !sidebarCollapsed" x-collapse x-cloak class="ms-8 space-y-1 border-l-2 border-gray-100 pl-4">
                                <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">Users</a>
                                <a href="{{ route('admin.roles.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.roles.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">Roles</a>
                                <a href="{{ route('admin.permissions.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.permissions.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">Permissions</a>
                            </div>
                        </div>
                    @endif

                    <div x-data="{ open: {{ request()->routeIs('admin.blogs.*') || request()->routeIs('admin.cms-pages.*') || request()->routeIs('admin.faqs.*') ? 'true' : 'false' }} }" class="space-y-1">
                        <button @click="open=!open" title="Content" class="w-full flex items-center text-sm font-medium transition-all duration-200"
                                :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-10 h-10 rounded-lg mx-auto' : 'gap-3 px-3 py-2.5 rounded-lg'"
                                :class="open ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'">
                            <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 006.5 22h11A2.5 2.5 0 0020 19.5v-15A2.5 2.5 0 0017.5 2h-11A2.5 2.5 0 004 4.5v15z"/></svg>
                            <span x-show="!sidebarCollapsed" class="flex-1 text-left truncate">Content</span>
                            <svg x-show="!sidebarCollapsed" class="h-4 w-4 ms-auto flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.127l3.71-3.896a.75.75 0 111.08 1.04l-4.24 4.46a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                        </button>
                        <div x-show="open && !sidebarCollapsed" x-collapse x-cloak class="ms-8 space-y-1 border-l-2 border-gray-100 pl-4">
                            <a href="{{ route('admin.blogs.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.blogs.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">Blogs</a>
                            <a href="{{ route('admin.cms-pages.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.cms-pages.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">CMS Pages</a>
                            <a href="{{ route('admin.faqs.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('admin.faqs.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">FAQs</a>
                        </div>
                    </div>

                    <a href="{{ route('admin.settings.edit') }}"
                       title="Site Settings"
                       :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-10 h-10 rounded-lg mx-auto' : 'gap-3 px-3 py-2.5 rounded-lg'"
                       class="flex items-center text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35.48-.116.88-.41 1.065-2.572.94-1.543 3.31-.826 2.37-2.37.185-2.162.585-2.456 1.065-2.572z"/></svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Site Settings</span>
                    </a>
                </nav>

                <!-- Removed bottom collapse button -->
            </aside>

            <!-- Main -->
            <div :class="sidebarCollapsed ? 'md:ms-20' : 'md:ms-64'" class="transition-[margin] duration-300 admin-shell">
                <!-- Topbar -->
                <header class="sticky top-0 z-20 bg-white border-b border-gray-200/80 shadow-sm">
                    <div class="h-16 flex items-center gap-4 px-6">
                        <button class="md:hidden rounded-lg p-2 hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors" @click="sidebarOpen=true">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <div class="ms-auto flex items-center gap-3">
                            @auth
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-primary-100 text-primary-600 text-sm font-semibold">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                            </div>
                                            <span class="hidden sm:block">{{ Auth::user()->name }}</span>
                                            <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('profile.edit')">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.settings.edit')">
                                            {{ __('Site Settings') }}
                                        </x-dropdown-link>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @endauth
                        </div>
                    </div>
                </header>

                <main class="p-6">
                    @if (isset($header))
                        <div class="mb-6">
                            {{ $header }}
                        </div>
                    @endif
                    {{ $slot }}
                </main>

                @stack('scripts')
            </div>
        </div>
    </body>
</html>


