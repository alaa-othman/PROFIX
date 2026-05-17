@extends('layouts.dashboardtemplate')

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Stats Cards -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm">Total Revenue</h3>
        <p class="text-2xl font-bold">$12,345</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm">Active Jobs</h3>
        <p class="text-2xl font-bold">23</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm">Customers</h3>
        <p class="text-2xl font-bold">456</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm">Pending Tasks</h3>
        <p class="text-2xl font-bold">12</p>
    </div>
</div>

<!-- Role-specific widgets -->
@role('admin')
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Admin Controls</h3>
        <p>Admin-specific content here</p>
    </div>
@endrole

@role('mechanic')
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Today's Schedule</h3>
        <p>Mechanic-specific content here</p>
    </div>
@endrole
@endsection