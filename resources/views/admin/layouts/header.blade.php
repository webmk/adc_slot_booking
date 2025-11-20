<div class="flex items-center justify-between px-6 py-3 mt-1">
  <div class="flex items-center space-x-4">
    <button id="mobile-menu-btn" class="md:hidden p-2 rounded hover:bg-gray-100" aria-label="Toggle menu">
      <!-- simple hamburger -->
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>

    <div>
      <h1 class="text-lg font-semibold">@yield('page-title', 'Admin Dashboard')</h1>
      <!-- <p class="text-sm text-gray-500">@yield('page-subtitle')</p> -->
    </div>
  </div>

  <div class="flex items-center space-x-4">
    <div class="flex items-center space-x-3">
      <span class="text-sm text-gray-600 hidden sm:inline">Signed in as <strong>{{ auth()->user()->name }}</strong></span>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-500 text-sm">Logout</button>
      </form>
    </div>
  </div>
</div>