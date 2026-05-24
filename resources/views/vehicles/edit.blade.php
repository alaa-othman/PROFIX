@extends('layouts.dashboardtemplate')

@section('title', 'Edit Vehicle')
@section('header', 'Vehicles')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-2xl font-bold text-gray-900">Edit Vehicle</h4>
        <a href="{{ route('vehicles.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Vehicles
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Vehicle Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Vehicle Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror"
                                required>
                            <option value="">Select Vehicle Type</option>
                            <option value="Car" {{ old('type', $vehicle->type) == 'Car' ? 'selected' : '' }}>Car</option>
                            <option value="Motorcycle" {{ old('type', $vehicle->type) == 'Motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                            <option value="Truck" {{ old('type', $vehicle->type) == 'Truck' ? 'selected' : '' }}>Truck</option>
                            <option value="Van" {{ old('type', $vehicle->type) == 'Van' ? 'selected' : '' }}>Van</option>
                            <option value="SUV" {{ old('type', $vehicle->type) == 'SUV' ? 'selected' : '' }}>SUV</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehicle Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                            Color <span class="text-red-500">*</span>
                        </label>
                        <div class="flex space-x-2">
                            <input type="color" id="colorPicker" class="h-10 w-16 border border-gray-300 rounded" value="{{ old('color', $vehicle->color) }}">
                            <input type="text" name="color" id="color" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('color') border-red-500 @enderror"
                                   value="{{ old('color', $vehicle->color) }}"
                                   placeholder="Enter color name (e.g., Red, Blue, Black)"
                                   required>
                        </div>
                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Vehicle Model -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                            Model <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="model" id="model" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('model') border-red-500 @enderror"
                               value="{{ old('model', $vehicle->model) }}"
                               placeholder="e.g., 2020, 2021, 2022"
                               required>
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Owner -->
                    <div>
                        <label for="user_owned_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Owner <span class="text-red-500">*</span>
                        </label>
                        <select name="user_owned_id" id="user_owned_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('user_owned_id') border-red-500 @enderror"
                                required>
                            <option value="">Select Owner</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_owned_id', $vehicle->user_owned_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name ?? $user->first_name . ' ' . $user->last_name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_owned_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Buttons -->
                <div class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-200">
                    <a href="{{ route('vehicles.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-lg transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-lg transition duration-200">
                        Update Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const colorPicker = document.getElementById('colorPicker');
    const colorInput = document.getElementById('color');
    
    colorPicker.addEventListener('change', function() {
        colorInput.value = this.value;
    });
    
    colorInput.addEventListener('input', function() {
        colorPicker.value = this.value;
    });
</script>
@endsection