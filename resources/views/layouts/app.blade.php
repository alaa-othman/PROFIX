<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PROFIX')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-red-800">PROFIX</a>
                </div>
                <ul class="flex items-center space-x-4">
                    <li><a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Home</a></li>
                    <li><a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Services</a></li>
                    <li><a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">About</a></li>
                    <li><a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Contact</a></li>
                </ul>
                <div class="flex items-center space-x-4">
                    @auth
                        <div>
                            <span class="text-gray-700">{{ Auth::user()->full_name }}</span> <br>
                            <span class="text-gray-600">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-white font-bold hover:bg-red-600 p-2 bg-red-500">LOGOUT</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-white font-bold hover:bg-red-600 p-2 bg-red-500">SIGN IN</a>
                        {{-- <a href="{{ route('register') }}" class="text-gray-700 hover:text-gray-900">Register</a> --}}
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-8">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="max-w-7xl mx-auto px-4 mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>