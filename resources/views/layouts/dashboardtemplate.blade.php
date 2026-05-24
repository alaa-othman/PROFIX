<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - @yield('title', config('app.name'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Mobile sidebar transition */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        /* Hide scrollbar on mobile but keep functionality */
        @media (max-width: 768px) {
            .sidebar-mobile {
                transform: translateX(-100%);
                position: fixed;
                z-index: 40;
            }
            .sidebar-mobile.open {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen relative">
        <!-- Mobile Sidebar Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden" onclick="closeSidebar()"></div>
        
        <!-- Sidebar Component -->
        <div id="sidebar" class="sidebar-mobile sidebar-transition">
            <x-sidebar />
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="px-4 sm:px-6 py-4 flex justify-between items-center">
                    <!-- Center header title - hidden on mobile -->
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-800 hidden md:inline">
                        @yield('title')
                    </h1>
                    <!-- Left section with menu button and logo -->
                    <div class="flex items-center space-x-3">
                        <!-- Mobile Menu Button -->
                        <button 
                            id="menuButton"
                            onclick="toggleSidebar()"
                            class="md:hidden p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                            aria-label="Toggle menu"
                        >
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <!-- Logo - Visible on all screens, text hidden on mobile -->
                        <div class="flex items-center">
                            <!-- Replace with your actual logo image -->
                            {{-- <img src="{{ asset('images/profix-logo.png') }}" alt="Profix" class="h-8 w-auto"> --}}
                            <span class="ml-2 text-xl font-bold text-gray-800 md:hidden">P R O F I X</span>
                        </div>
                    </div>
                    
                    
                    
                    <!-- Right section with user info and logout -->
                    <div class="flex items-center space-x-3 sm:space-x-4">
                        <!-- User name - hidden on very small screens -->
                        <span class="text-xs sm:text-sm text-gray-600 hidden xs:inline">
                            {{ Auth::user()->name }} 
                            ({{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }})
                        </span>
                        
                        <!-- Logout button - icon only on mobile -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm flex items-center">
                                <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Mobile header title - only visible on mobile -->
                <div class="md:hidden px-4 pb-3">
                    <h1 class="text-lg font-semibold text-gray-800">
                        @yield('title')
                    </h1>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6">
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

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
            
            // Prevent body scroll when sidebar is open on mobile
            if (sidebar.classList.contains('open')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
        
        // Close sidebar function
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.remove('open');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        // Close sidebar on window resize (if switching from mobile to desktop)
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) { // md breakpoint
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                
                sidebar.classList.remove('open');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
        
        // Close sidebar when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSidebar();
            }
        });
        
        // Close sidebar when clicking a link (optional)
        document.querySelectorAll('#sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    closeSidebar();
                }
            });
        });
    </script>
</body>
</html>