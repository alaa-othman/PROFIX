@extends('layouts.dashboardtemplate')

@section('title', 'Vehicle Details')
@section('header', 'Vehicles')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-2xl font-bold text-gray-900">Vehicle Details</h4>
        <div class="space-x-3">
            <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Vehicle
            </a>
            <a href="{{ route('vehicles.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Vehicles
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Vehicle ID</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $vehicle->id }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Vehicle Type</label>
                    <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full 
                        @if($vehicle->type == 'Car') bg-blue-100 text-blue-800
                        @elseif($vehicle->type == 'Motorcycle') bg-green-100 text-green-800
                        @elseif($vehicle->type == 'Truck') bg-purple-100 text-purple-800
                        @elseif($vehicle->type == 'SUV') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $vehicle->type }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Color</label>
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full mr-2 border border-gray-300" style="background-color: {{ $vehicle->color }}"></div>
                        <p class="text-lg font-semibold text-gray-900">{{ ucfirst($vehicle->color) }}</p>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Model</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $vehicle->model }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Owner Information</label>
                    @if($vehicle->owner)
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $vehicle->owner->name ?? $vehicle->owner->first_name . ' ' . $vehicle->owner->last_name }}
                        </p>
                        <p class="text-sm text-gray-600">{{ $vehicle->owner->email }}</p>
                    @else
                        <p class="text-lg font-semibold text-gray-500">No owner assigned</p>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Created At</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $vehicle->created_at->format('F d, Y H:i:s') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $vehicle->updated_at->format('F d, Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection