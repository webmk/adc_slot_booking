<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Login - {{ config('app.name') }}</title>
    @vite('resources/css/app.css')

    <style>
        body {
            background: linear-gradient(135deg, #2563eb, #1e3a8a);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn .6s ease-out;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center px-4 relative">
    <div class="absolute top-8 left-8 flex items-center gap-3">
        <img src="{{ asset('images/ongc.png') }}" alt="ONGC Logo" class="w-14 h-14">
    </div>
    <h1 class="text-white text-3xl font-bold mb-10 text-center drop-shadow-lg">
        Welcome to Online ADC ONGC
    </h1>
    <div class="w-full max-w-md bg-white shadow-2xl rounded-xl p-8 animate-fadeIn">
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-2">
            Login
        </h2>
        <p class="text-gray-500 text-center mb-6 text-sm">
            Sign in to access your dashboard
        </p>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 text-red-700 text-sm px-4 py-2 rounded">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CPF Number</label>
                <input type="text" name="cpf_no" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
            </div>
            <button
                class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition cursor-pointer">
                Sign In
            </button>
        </form>
    </div>
</body>
</html>