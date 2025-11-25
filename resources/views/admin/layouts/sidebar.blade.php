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
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          ADC Dates
        </a>
      </li>
      <li>
        <a href="{{ route('admin.adc-centres.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-50 {{ request()->is('admin/adc-centres*') ? 'bg-gray-100 font-semibold' : '' }}">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="12" cy="10" r="3" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M12 2C7.58172 2 4 5.58172 4 10C4 11.8919 4.40209 13.1304 5.5 14.5L12 22L18.5 14.5C19.5979 13.1304 20 11.8919 20 10C20 5.58172 16.4183 2 12 2Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          ADC Centers
        </a>
      </li>
      <li>
        <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-50 {{ request()->is('admin/bookings*') ? 'bg-gray-100 font-semibold' : '' }}">
          <svg class="w-5 h-5" viewBox="0 0 16 16" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
            <rect height="12.5" width="10.5" y="1.75" x="2.75" />
            <path d="m5.75 7.75h4.5m-4.5 3h2.5m-2.5-6h4.5" />
          </svg>
          Bookings
        </a>
      </li>
      <li>
        <a href="{{ route('admin.location-mappings.index') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-50 {{ request()->is('admin/location-mappings*') ? 'bg-gray-100 font-semibold' : '' }}">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="12" cy="10" r="3" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M12 2C7.58172 2 4 5.58172 4 10C4 11.8919 4.40209 13.1304 5.5 14.5L12 22L18.5 14.5C19.5979 13.1304 20 11.8919 20 10C20 5.58172 16.4183 2 12 2Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Locations
        </a>
      </li>
      <li>
        <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-50">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M3 7h18M3 12h18M3 17h18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Reports
        </a>
      </li>
    </ul>
  </nav>
</div>