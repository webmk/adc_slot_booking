<div class="h-full flex flex-col">
  <div class="p-4 border-b">
    <a href="{{ route('admin.dashboard') }}" class="block text-xl font-bold">{{ config('app.name') }}</a>
    <p class="text-xs text-gray-500 mt-1">Admin Panel</p>
  </div>
  <nav class="flex-1 px-3 py-4 overflow-auto">
    <ul class="space-y-1">
      <li>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-50 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 font-semibold' : '' }}">
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M3 12h18M3 6h18M3 18h18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
          Dashboard
        </a>
      </li>
      <li>
        <a href="{{ route('admin.adc-dates.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-50 {{ request()->is('admin/adc-dates*') ? 'bg-gray-100 font-semibold' : '' }}">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          ADC Dates
        </a>
      </li>
      <li>
        <a href="{{ route('admin.adc-centres.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-50 {{ request()->is('admin/adc-centres*') ? 'bg-gray-100 font-semibold' : '' }}">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          ADC Centers
        </a>
      </li>
      <li>
        <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-50 {{ request()->is('admin/bookings*') ? 'bg-gray-100 font-semibold' : '' }}">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Bookings
        </a>
      </li>
      <li>
        <a href="{{ route('admin.location-mappings.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-50 {{ request()->is('admin/location-mappings*') ? 'bg-gray-100 font-semibold' : '' }}">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Locations
        </a>
      </li>
      <li>
        <a href="#" class="flex items-center gap-3 p-2 rounded hover:bg-gray-50">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 7h18M3 12h18M3 17h18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Reports
        </a>
      </li>
    </ul>
  </nav>
</div>