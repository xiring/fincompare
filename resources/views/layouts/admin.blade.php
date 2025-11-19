<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('storage/' . $siteSettings->favicon) }}">

        <title>{{ $siteSettings->site_name }} - Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false, sidebarCollapsed: false }" class="min-h-screen bg-gray-100">
            <x-toast />
            @stack('styles')

            <!-- Mobile overlay -->
            <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-30 bg-black/50 md:hidden" @click="sidebarOpen=false"></div>

            <!-- Sidebar -->
            <aside
                x-bind:class="sidebarCollapsed ? 'md:w-20' : 'md:w-64'"
                class="fixed inset-y-0 left-0 z-40 w-64 md:translate-x-0 -translate-x-full md:fixed md:flex md:flex-col bg-white border-r border-gray-200 overflow-y-auto transition-all duration-200"
                :class="{ 'translate-x-0': sidebarOpen }"
            >
                <div class="h-16 flex items-center px-4 border-b">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="h-8 w-8 text-gray-800" />
                        <span x-show="!sidebarCollapsed" class="font-semibold text-gray-800">Admin</span>
                    </a>
                    <!-- Desktop collapse toggle -->
                    <button @click="sidebarCollapsed=!sidebarCollapsed"
                            class="ms-auto hidden md:inline-flex items-center justify-center rounded p-2 hover:bg-gray-100"
                            aria-label="Toggle sidebar">
                        <svg class="h-5 w-5 text-gray-600 transition-transform"
                             :class="sidebarCollapsed ? 'rotate-180' : ''"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <!-- Mobile close button -->
                    <button class="ms-auto md:hidden rounded p-2 hover:bg-gray-100" @click="sidebarOpen=false">
                        <svg class="h-5 w-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="p-3 space-y-1 text-sm">
                    <a href="{{ route('dashboard') }}"
                       title="Dashboard"
                       :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-9 h-9 rounded-full mx-auto' : 'gap-3 px-3 py-2 rounded-md'"
                       class="flex items-center hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                        <span x-show="!sidebarCollapsed">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.partners.index') }}"
                       title="Partners"
                       :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-9 h-9 rounded-full mx-auto' : 'gap-3 px-3 py-2 rounded-md'"
                       class="flex items-center hover:bg-gray-100 {{ request()->routeIs('admin.partners.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 10-8 0v4m-2 4h12a2 2 0 012 2v3H2v-3a2 2 0 012-2z"/></svg>
                        <span x-show="!sidebarCollapsed">Partners</span>
                    </a>

                    <!-- Catalog -->
                    <div x-data="{ open: {{ request()->routeIs('admin.product-categories.*') || request()->routeIs('admin.products.*') || request()->routeIs('admin.attributes.*') ? 'true' : 'false' }} }" class="space-y-1">
                        <button @click="open=!open" title="Catalog" class="w-full flex items-center hover:bg-gray-100 text-gray-700"
                                :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-9 h-9 rounded-full mx-auto' : 'gap-3 px-3 py-2 rounded-md'"
                                :class="{ 'bg-gray-100 text-gray-900': open }">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span x-show="!sidebarCollapsed" class="flex-1 text-left">Catalog</span>
                            <svg x-show="!sidebarCollapsed" class="h-4 w-4 ms-auto" viewBox="0 0 20 20"><path fill="currentColor" :class="open ? 'rotate-180' : ''" class="transform transition" d="M5.23 7.21a.75.75 0 011.06.02L10 11.127l3.71-3.896a.75.75 0 111.08 1.04l-4.24 4.46a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"/></svg>
                        </button>
                        <div x-show="open && !sidebarCollapsed" x-collapse x-cloak class="ms-10 space-y-1">
                            <a href="{{ route('admin.product-categories.index') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100 {{ request()->routeIs('admin.product-categories.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">Categories</a>
                            <a href="{{ route('admin.products.index') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100 {{ request()->routeIs('admin.products.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">Products</a>
                            <a href="{{ route('admin.attributes.index') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100 {{ request()->routeIs('admin.attributes.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">Attributes</a>
                        </div>
                    </div>

                    <a href="{{ route('admin.leads.index') }}"
                       title="Leads"
                       :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-9 h-9 rounded-full mx-auto' : 'gap-3 px-3 py-2 rounded-md'"
                       class="flex items-center hover:bg-gray-100 {{ request()->routeIs('admin.leads.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z"/></svg>
                        <span x-show="!sidebarCollapsed">Leads</span>
                    </a>

                    <a href="{{ route('admin.activity.index') }}"
                       title="Activity"
                       :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-9 h-9 rounded-full mx-auto' : 'gap-3 px-3 py-2 rounded-md'"
                       class="flex items-center hover:bg-gray-100 {{ request()->routeIs('admin.activity.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 12h2m-1-9a9 9 0 100 18 9 9 0 000-18z"/></svg>
                        <span x-show="!sidebarCollapsed">Activity</span>
                    </a>

                    @if(auth()->user()?->hasRole('admin'))
                        <div x-data="{ open: {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'true' : 'false' }} }" class="space-y-1">
                            <button @click="open=!open" title="Access" class="w-full flex items-center hover:bg-gray-100 text-gray-700"
                                    :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-9 h-9 rounded-full mx-auto' : 'gap-3 px-3 py-2 rounded-md'"
                                    :class="{ 'bg-gray-100 text-gray-900': open }">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span x-show="!sidebarCollapsed" class="flex-1 text-left">Access</span>
                                <svg x-show="!sidebarCollapsed" class="h-4 w-4 ms-auto" viewBox="0 0 20 20"><path fill="currentColor" :class="open ? 'rotate-180' : ''" class="transform transition" d="M5.23 7.21a.75.75 0 011.06.02L10 11.127l3.71-3.896a.75.75 0 111.08 1.04l-4.24 4.46a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"/></svg>
                            </button>
                            <div x-show="open && !sidebarCollapsed" x-collapse x-cloak class="ms-10 space-y-1">
                                <a href="{{ route('admin.users.index') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">Users</a>
                                <a href="{{ route('admin.roles.index') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100 {{ request()->routeIs('admin.roles.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">Roles</a>
                                <a href="{{ route('admin.permissions.index') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100 {{ request()->routeIs('admin.permissions.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">Permissions</a>
                            </div>
                        </div>
                    @endif

                    <div x-data="{ open: {{ request()->routeIs('admin.blogs.*') || request()->routeIs('admin.cms-pages.*') || request()->routeIs('admin.faqs.*') ? 'true' : 'false' }} }" class="space-y-1">
                        <button @click="open=!open" title="Content" class="w-full flex items-center hover:bg-gray-100 text-gray-700"
                                :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-9 h-9 rounded-full mx-auto' : 'gap-3 px-3 py-2 rounded-md'"
                                :class="{ 'bg-gray-100 text-gray-900': open }">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19.5A2.5 2.5 0 006.5 22h11A2.5 2.5 0 0020 19.5v-15A2.5 2.5 0 0017.5 2h-11A2.5 2.5 0 004 4.5v15z"/></svg>
                            <span x-show="!sidebarCollapsed" class="flex-1 text-left">Content</span>
                            <svg x-show="!sidebarCollapsed" class="h-4 w-4 ms-auto" viewBox="0 0 20 20"><path fill="currentColor" :class="open ? 'rotate-180' : ''" class="transform transition" d="M5.23 7.21a.75.75 0 011.06.02L10 11.127l3.71-3.896a.75.75 0 111.08 1.04l-4.24 4.46a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"/></svg>
                        </button>
                        <div x-show="open && !sidebarCollapsed" x-collapse x-cloak class="ms-10 space-y-1">
                            <a href="{{ route('admin.blogs.index') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100 {{ request()->routeIs('admin.blogs.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">Blogs</a>
                            <a href="{{ route('admin.cms-pages.index') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100 {{ request()->routeIs('admin.cms-pages.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">CMS Pages</a>
                            <a href="{{ route('admin.faqs.index') }}" class="block px-3 py-1.5 rounded hover:bg-gray-100 {{ request()->routeIs('admin.faqs.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">FAQs</a>
                        </div>
                    </div>

                    <a href="{{ route('admin.settings.edit') }}"
                       title="Site Settings"
                       :class="sidebarCollapsed ? 'justify-center gap-0 p-0 w-9 h-9 rounded-full mx-auto' : 'gap-3 px-3 py-2 rounded-md'"
                       class="flex items-center hover:bg-gray-100 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35.48-.116.88-.41 1.065-2.572.94-1.543 3.31-.826 2.37-2.37.185-2.162.585-2.456 1.065-2.572z"/></svg>
                        <span x-show="!sidebarCollapsed">Site Settings</span>
                    </a>
                </nav>

                <!-- Removed bottom collapse button -->
            </aside>

            <!-- Main -->
            <div :class="sidebarCollapsed ? 'md:ms-20' : 'md:ms-64'" class="transition-[margin] duration-200 admin-shell">
                <!-- Topbar -->
                <header class="sticky top-0 z-20 bg-white border-b">
                    <div class="h-10 flex items-center gap-3 px-4">
                        <button class="md:hidden rounded p-2 hover:bg-gray-100" @click="sidebarOpen=true">
                            <svg class="h-5 w-5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <div class="ms-auto flex items-center gap-2">
                            @auth
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 bg-white hover:text-gray-900 focus:outline-none transition">
                                            <div>{{ Auth::user()->name }}</div>
                                            <svg class="ms-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
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

                <main class="py-2">
                    @if (isset($header))
                        <div class="{{ request()->routeIs('dashboard') ? 'py-2 px-4 sm:px-6 lg:px-8' : 'max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8' }}">
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


