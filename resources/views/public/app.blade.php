<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $siteSettings->site_name ?? 'FinCompare' }}</title>

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
    @vite(['resources/css/app.css', 'resources/js/public/app.js'])

    <!-- Pass site settings to Vue -->
    <script>
        window.__SITE_SETTINGS__ = @json($siteSettings ?? null);
    </script>
</head>
<body class="font-sans antialiased">
    <div id="app"></div>
</body>
</html>
