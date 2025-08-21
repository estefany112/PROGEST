<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ProGest') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative min-h-screen font-sans antialiased">
    <div class="absolute inset-0 bg-cover bg-center z-0" style="background-image: url('{{ asset('assets/img/icono.png') }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 to-black/40 z-0"></div>

    <div class="relative z-10 flex items-center justify-center min-h-screen px-4">
        {{ $slot }}
    </div>
</body>

</html>
