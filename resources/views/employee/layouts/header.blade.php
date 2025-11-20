<header class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        
        <h1 class="text-xl font-semibold text-gray-800">
            ADC Portal
        </h1>

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