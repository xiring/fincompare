<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $siteSettings->site_name }} - {{ $siteSettings->site_slogon }}</title>
        <meta name="description" content="{{ $siteSettings->seo_description }}">
        <meta name="keywords" content="{{ $siteSettings->seo_keywords }}">
        <meta name="author" content="{{ $siteSettings->site_name }}">
        <meta name="robots" content="index, follow">
        <meta name="googlebot" content="index, follow">
        <meta name="bingbot" content="index, follow">
        <meta name="yandexbot" content="index, follow">
        <link rel="icon" href="{{ asset('storage/' . $siteSettings->favicon) }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body x-data="{ loaded:false }" x-init="window.addEventListener('load', ()=>loaded=true)" class="font-sans antialiased theme-public text-[var(--brand-text)]">
        <div class="min-h-screen bg-[var(--brand-bg)] flex flex-col">
            @include('layouts.guest-nav')
			<x-toast />
            @stack('styles')
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
        <!-- Loading Overlay -->
        <div x-show="!loaded" x-cloak class="fixed inset-0 z-50 bg-white/90 backdrop-blur flex items-center justify-center">
            <div class="flex flex-col items-center">
                <svg class="h-8 w-8 animate-spin text-[color:var(--brand-primary)]" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <div class="mt-3 text-sm text-gray-700">Loading…</div>
            </div>
        </div>
        @stack('scripts')
        <x-footer />
        <x-cookie-consent />
    </body>
</html>
