<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - @yield('title', config('app.name'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar Component -->
        <x-sidebar />
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-800">
                        @yield('header')
                    </h1>
                    
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">
                            {{ Auth::user()->name }} 
                            ({{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }})
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>