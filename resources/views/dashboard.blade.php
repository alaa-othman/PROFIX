@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h2 class="text-lg font-semibold text-blue-800 mb-2">Welcome, {{ Auth::user()->full_name }}!</h2>
            <p class="text-blue-700">Role: {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</p>
            <p class="text-blue-700">Email: {{ Auth::user()->email }}</p>
            <p class="text-blue-700">Phone: {{ Auth::user()->phone }}</p>
            <p class="text-blue-700">Registered at: {{ Auth::user()->registered_at->format('Y-m-d H:i:s') }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @if(Auth::user()->isAdmin())
                <div class="bg-gray-50 rounded-lg p-4 border">
                    <h3 class="font-semibold text-lg mb-2">Admin Panel</h3>
                    <p>Admin-specific features here</p>
                </div>
            @endif
            
            @if(Auth::user()->isMechanic())
                <div class="bg-gray-50 rounded-lg p-4 border">
                    <h3 class="font-semibold text-lg mb-2">Mechanic Panel</h3>
                    <p>Mechanic-specific features here</p>
                </div>
            @endif
            
            @if(Auth::user()->isCallCenter())
                <div class="bg-gray-50 rounded-lg p-4 border">
                    <h3 class="font-semibold text-lg mb-2">Call Center Panel</h3>
                    <p>Call center-specific features here</p>
                </div>
            @endif
            
            @if(Auth::user()->isAccountant())
                <div class="bg-gray-50 rounded-lg p-4 border">
                    <h3 class="font-semibold text-lg mb-2">Accounting Panel</h3>
                    <p>Accounting-specific features here</p>
                </div>
            @endif
            
            @if(Auth::user()->isCustomer())
                <div class="bg-gray-50 rounded-lg p-4 border">
                    <h3 class="font-semibold text-lg mb-2">Customer Panel</h3>
                    <p>Customer-specific features here</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection