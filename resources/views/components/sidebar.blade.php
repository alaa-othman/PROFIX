@php 
    $userRole = Auth::user()->role ?? 'customer';
    $menuItemsForAll = [
        ['name' => 'Send Order', 'route' => 'orders.create', 'icon' => 'home'],
        ['name' => 'Orders', 'route' => 'orders.index', 'icon' => 'home'],
        ['name' => 'Add Vehicle', 'route' => 'vehicles.create', 'icon' => 'home'],
        ['name' => 'Vehicles', 'route' => 'vehicles.index', 'icon' => 'home'],
    ];
    $menuItemsNew = [];

    if ($userRole === 'admin') {
        $menuItemsNew = [
            ['name' => 'Manage Users', 'route' => 'dashboard', 'icon' => 'users'],
            ['name' => 'Settings', 'route' => 'dashboard', 'icon' => 'settings'],
        ];
    } elseif ($userRole === 'mechanic') {
        $menuItemsNew = [
            ['name' => 'My Schedule', 'route' => 'dashboard', 'icon' => 'clock'],
        ];
    }
    $menuItems = array_merge($menuItemsNew, $menuItemsForAll);
@endphp

<aside class="w-64 bg-gray-900 text-white flex flex-col min-h-screen">
    <!-- Logo/Brand -->
    <div class="p-6 border-b border-gray-800">
        <h2 class="text-2xl font-bold text-white">P R O F I X</h2>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">
            @foreach($menuItems as $item)
                <li>
                    <a href="{{ route($item['route']) }}" 
                       class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition-colors duration-200
                              {{ request()->routeIs($item['route']) ? 'bg-gray-800 text-white border-r-4 border-blue-500' : '' }}">
                        <i data-feather="{{ $item['icon'] }}" class="w-5 h-5 mr-3"></i>
                        <span>{{ $item['name'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
    
    <!-- User Info Footer -->
    <div class="p-4 border-t border-gray-800 text-xs text-gray-400">
        <div>Logged in as:</div>
        <div class="font-semibold text-white">{{ Auth::user()->name }}</div>
        <div class="text-gray-500">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</div>
    </div>
</aside>

{{-- @push('scripts')
<script>
    // Initialize Feather icons
    feather.replace();
</script>
@endpush --}}