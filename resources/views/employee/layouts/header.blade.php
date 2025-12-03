<header class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex gap-4">
        <img src="{{ asset('images/ongc_logo.png') }}" width="120" height="120" alt="Logo" />
        <h1 class="text-xl font-semibold text-gray-800 mt-2">
            ADC Portal
        </h1>
        </div>
        <nav class="flex items-center gap-6">
            <a href="{{ route('employee.index') }}"
               class="text-gray-700 hover:text-blue-600">
               Book Slot
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Logout
                </button>
            </form>
        </nav>
    </div>
</header>