<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'ProGest') }}</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4">
                {{ $header ?? '' }}
            </div>
        </header>

        <main class="py-6 px-4">
            {{ $slot }}
        </main>
    </div>
</body>
</html>

