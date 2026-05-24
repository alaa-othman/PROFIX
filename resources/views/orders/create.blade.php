@extends('layouts.dashboardtemplate')

@section('title', 'Create New Order')
@section('header', 'Send Order')

@section('content')
<div class="container mx-auto px-4">
    @if(Auth::user()->role !== 'customer')
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Orders
        </a>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <p class="mx-6 mt-6 font-medium text-red-500 text-sm">Note : You have to add your vehicle informations before send an order.
            <a href="{{ route('vehicles.create') }}" class="text-blue-500 hover:text-blue-700">click here</a>
        </p>
        <div class="p-6">
            <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                    <!-- Order Title -->
                    <div>
                        <label for="order_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Order Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('order_title') border-red-500 @enderror" 
                               id="order_title" 
                               name="order_title" 
                               value="{{ old('order_title') }}"
                               placeholder="Enter order title"
                               required>
                        @error('order_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Selection -->
                    <div>
                        <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Vehicle
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vehicule_id') border-red-500 @enderror" 
                                id="vehicule_id" 
                                name="vehicule_id" 
                                required>
                            <option value="">Select Vehicle</option>
                            @if(Auth::user()->role === 'customer')
                                @foreach($vehicles as $vehicle)
                                    @if($vehicle->user_owned_id == Auth::user()->id)
                                        <option value="{{ $vehicle->id }}" 
                                            {{ old('vehicule_id') == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->model }}
                                        </option>
                                    @endif
                                @endforeach
                            @else
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" 
                                        {{ old('vehicule_id') == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->model }} - {{ $vehicle->plate_number ?? 'No Plate' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('vehicule_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                    
                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location') border-red-500 @enderror" 
                               id="location" 
                               name="location" 
                               value="{{ old('location') }}"
                               placeholder="Enter location">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Customer Selection -->
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Customer <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('customer_id') border-red-500 @enderror" 
                                id="customer_id" 
                                name="customer_id" 
                                required>
                            @if (Auth::user()->role === 'customer')
                                <option value="{{ Auth::user()->id }}" selected>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</option>
                            @else
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->first_name }} {{ $customer->last_name }}
                                    </option>
                                @endforeach
                            @endif
                            
                        </select>
                        @error('customer_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if (Auth::user()->role !== 'customer')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                    <!-- Assigned Employee -->
                    <div>
                        <label for="leader_employee_id" class="block text-sm font-medium text-gray-700 mb-2">Assign Employee (Optional)</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('leader_employee_id') border-red-500 @enderror" 
                                id="leader_employee_id" 
                                name="leader_employee_id">
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" 
                                    {{ old('leader_employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->first_name }} {{ $employee->last_name }} - {{ $employee->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('leader_employee_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Amount -->
                    <div>
                        <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">Total Amount ($)</label>
                        <input type="number" 
                               step="0.01" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('total_amount') border-red-500 @enderror" 
                               id="total_amount" 
                               name="total_amount" 
                               value="{{ old('total_amount')}}"
                               placeholder="0.00"
                               readonly={{ Auth::user()->role === 'customer' ? 'readonly' : '' }}>
                        @error('total_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror" 
                                id="status" 
                                name="status">
                            <option value="Received" {{ old('status') == 'Received' ? 'selected' : '' }}>Received</option>
                            <option value="In Progress..." {{ old('status') == 'In Progress...' ? 'selected' : '' }}>In Progress</option>
                            <option value="Waiting Products..." {{ old('status') == 'Waiting Products...' ? 'selected' : '' }}>Waiting Products</option>
                            <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Status -->
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_status') border-red-500 @enderror" 
                                id="payment_status" 
                                name="payment_status">
                            <option value="unpaid" {{ old('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        @error('payment_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                    <!-- Start Date -->
                    <div>
                        <label for="start_at" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="datetime-local" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_at') border-red-500 @enderror" 
                               id="start_at" 
                               name="start_at" 
                               value="{{ old('start_at') }}">
                        @error('start_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_at" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="datetime-local" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_at') border-red-500 @enderror" 
                               id="end_at" 
                               name="end_at" 
                               value="{{ old('end_at') }}">
                        @error('end_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @endif

                <!-- Description -->
                <div class="mt-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" 
                              id="description" 
                              name="description" 
                              rows="4" 
                              placeholder="Enter order description...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Buttons -->
                <div class="flex justify-between items-center mt-8 pt-4 border-t border-gray-200">
                    <button type="reset" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-lg transition duration-200 inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset
                    </button>
                    <div>
                        <a href="{{ route('orders.index') }}" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-6 rounded-lg transition duration-200 inline-flex items-center mr-3">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-lg transition duration-200 inline-flex items-center" id="submitBtn">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Send Order
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Form validation and auto-calculation
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Creating...';
    });

    // Auto-calculate end date cannot be before start date
    const startDate = document.getElementById('start_at');
    const endDate = document.getElementById('end_at');
    
    startDate.addEventListener('change', function() {
        if (endDate.value && endDate.value < this.value) {
            endDate.value = '';
            alert('End date cannot be before start date.');
        }
    });
    
    endDate.addEventListener('change', function() {
        if (startDate.value && this.value < startDate.value) {
            this.value = '';
            alert('End date cannot be before start date.');
        }
    });
</script>
@endsection