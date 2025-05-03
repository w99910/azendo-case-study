<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    {{-- Inline script to detect system dark mode preference and apply it immediately --}}
    <script></script>

    {{-- Inline style to set the HTML background color based on our theme in app.css --}}
    <style>

    </style>

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    @inertiaHead
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
</head>

<body class="font-sans antialiased">
    @vite(['resources/js/app.js'])
    @inertia
</body>

</html>
