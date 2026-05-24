@extends('layouts.dashboardtemplate')

@section('title', 'Order Details')
@section('header', 'Order Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-2xl font-bold text-gray-900">Order Details #{{ $order->id }}</h4>
        <div class="space-x-3">
            <a href="{{ route('orders.edit', $order->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Order
            </a>
            <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Orders
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Order ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Order ID</label>
                    <p class="text-lg font-semibold text-gray-900">#{{ $order->id }}</p>
                </div>

                <!-- Order Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Order Title</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $order->order_title }}</p>
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Location</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $order->location ?? 'N/A' }}</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                        {{ $order->status == 'Completed' ? 'bg-green-100 text-green-800' : 
                           ($order->status == 'In Progress...' ? 'bg-yellow-100 text-yellow-800' : 
                           ($order->status == 'Waiting Products...' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')) }}">
                        {{ $order->status }}
                    </span>
                </div>

                <!-- Payment Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Payment Status</label>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                        {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>

                <!-- Total Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Total Amount</label>
                    <p class="text-2xl font-bold text-blue-600">{{ $order->getFormattedTotalAmount() }}</p>
                </div>

                <!-- Customer Information -->
                <div class="col-span-1 md:col-span-2 mt-4">
                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                    </div>
                </div>

                <!-- Customer ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Customer ID</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $order->customer->id ?? 'N/A' }}</p>
                </div>

                <!-- Customer Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Customer Name</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $order->customer->first_name ?? 'N/A' }} {{ $order->customer->last_name ?? 'N/A' }}</p>
                </div>

                <!-- Customer Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Customer Email</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $order->customer->email ?? 'N/A' }}</p>
                </div>

                <!-- Customer Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Customer Phone</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $order->customer->phone ?? 'N/A' }}</p>
                </div>

                <!-- Vehicle Information -->
                <div class="col-span-1 md:col-span-2 mt-4">
                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Vehicle Information</h3>
                    </div>
                </div>

                <!-- Vehicle Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Vehicle Type</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $order->vehicule->type ?? 'N/A' }}</p>
                </div>

                <!-- Vehicle Model -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Vehicle Model</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $order->vehicule->model ?? 'N/A' }}</p>
                </div>

                <!-- Vehicle Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Vehicle Color</label>
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full mr-2 border border-gray-300" style="background-color: {{ $order->vehicule->color ?? '#000000' }}"></div>
                        <p class="text-lg font-semibold text-gray-900">{{ ucfirst($order->vehicule->color ?? 'N/A') }}</p>
                    </div>
                </div>

                <!-- Employee Information -->
                <div class="col-span-1 md:col-span-2 mt-4">
                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Assigned Employee</h3>
                    </div>
                </div>

                <!-- Employee Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Employee Name</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $order->leaderEmployee->name ?? 'Unassigned' }}</p>
                </div>

                <!-- Employee Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Employee Email</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $order->leaderEmployee->email ?? 'N/A' }}</p>
                </div>

                <!-- Timeline -->
                <div class="col-span-1 md:col-span-2 mt-4">
                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Timeline</h3>
                    </div>
                </div>

                <!-- Received At -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Received At</label>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $order->received_at ? $order->received_at->format('F d, Y H:i:s') : 'N/A' }}
                    </p>
                </div>

                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Start Date</label>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $order->start_at ? $order->start_at->format('F d, Y H:i:s') : 'Not started' }}
                    </p>
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">End Date</label>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $order->end_at ? $order->end_at->format('F d, Y H:i:s') : 'Not completed' }}
                    </p>
                </div>

                <!-- Duration -->
                @if($order->duration)
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Duration</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $order->duration }} days</p>
                </div>
                @endif

                <!-- Description -->
                @if($order->description)
                <div class="col-span-1 md:col-span-2 mt-4">
                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Description</h3>
                    </div>
                    <div class="prose max-w-none">
                        <p class="text-gray-700">{{ $order->description }}</p>
                    </div>
                </div>
                @endif

                <!-- System Information -->
                <div class="col-span-1 md:col-span-2 mt-4">
                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">System Information</h3>
                    </div>
                </div>

                <!-- Created At -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Created At</label>
                    <p class="text-base text-gray-900">{{ $order->created_at->format('F d, Y H:i:s') }}</p>
                </div>

                <!-- Updated At -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                    <p class="text-base text-gray-900">{{ $order->updated_at->format('F d, Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-3 mt-6">
        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-6 rounded-lg transition duration-200 inline-flex items-center" {{ $order->isCompleted() ? 'disabled' : '' }}>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete Order
            </button>
        </form>
        <a href="{{ route('orders.edit', $order->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-lg transition duration-200 inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Order
        </a>
    </div>
</div>
@endsection