@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="max-w-4xl mx-auto px-4 text-center">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome To PROFIX</h1>
        <p class="text-gray-600 mb-8">We are fixing your car wherever you are.</p>
        
        @guest
            <div class="space-x-4">
                <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    Login
                </a>
                <a href="{{ route('register') }}" class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700">
                    Register
                </a>
            </div>
        @endguest
        
        @auth
            <a href="{{ route('dashboard') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                Go to Dashboard
            </a>
        @endauth
    </div>
</div>
@endsection
