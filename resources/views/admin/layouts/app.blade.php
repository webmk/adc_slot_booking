<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>

  @vite('resources/css/app.css', 'resources/js/app.js')
  @stack('styles')
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex">
  <aside class="w-64 bg-white border-r hidden md:block">
    @include('admin.layouts.sidebar')
  </aside>
  <div class="flex-1 min-h-screen flex flex-col">
    <header class="bg-white border-b p-3">
      @include('admin.layouts.header')
    </header>
    <main class="p-6 flex-1">
      <div class="max-w-7xl mx-auto">
        @if(session('success'))
          <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">
            {{ session('success') }}
          </div>
        @endif

        @yield('content')
      </div>
    </main>

    <!-- <footer class="bg-white border-t">
      @include('admin.layouts.footer')
    </footer> -->
  </div>

  @stack('scripts')
</body>
</html>