<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title') - ADC Portal</title>

    @vite('resources/css/app.css')
    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

    @include('employee.layouts.header')

    <main class="flex-1 container mx-auto px-4 py-6">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>