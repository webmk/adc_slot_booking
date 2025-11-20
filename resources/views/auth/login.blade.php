<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Login – {{ config('app.name') }}</title>
     @vite('resources/css/app.css')
    <style>
        body {
            background: linear-gradient(135deg, #2563eb, #1e3a8a);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white shadow-2xl rounded-xl p-8 animate-fadeIn">

        <h2 class="text-3xl font-bold text-gray-800 text-center mb-2">
            Welcome Back
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="you@example.com" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="••••••••" />
            </div>
            <button
                class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Sign In
            </button>
            </div>
            
        </form>
    </div>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn .6s ease-out;
        }
    </style>

</body>
</html>