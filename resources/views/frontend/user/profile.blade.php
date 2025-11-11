@extends('frontend.layouts.app')
@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-white shadow-xl rounded-lg p-6 border border-gray-200 lg:col-span-1">
            <div class="flex items-center space-x-6 mb-6">
                <div class="flex-shrink-0">
                    <img class="w-24 h-24 rounded-full border-4 border-indigo-500 shadow-lg transform hover:scale-110 transition-all duration-300" 
                         src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://via.placeholder.com/150' }}" 
                         alt="Profile Image">
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ auth()->user()->email }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ auth()->user()->phone_number }}</p>
                    <p class="text-sm text-gray-500 mt-1">
                        <span class="font-semibold text-green-500">{{ auth()->user()->status == 1 ? 'Active' : 'Inactive' }}</span>
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-600">Name</label>
                    <p class="text-lg font-semibold text-gray-800">{{ old('name', auth()->user()->name) }}</p>
                </div>
                <div class="mb-4 ">
                    <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                    <p class="text-lg font-semibold text-gray-800">{{ old('email', auth()->user()->email) }}</p>
                </div>
                <div class="mb-4">
                    <label for="phone_number" class="block text-sm font-medium text-gray-600">Phone Number</label>
                    <p class="text-lg font-semibold text-gray-800">{{ old('phone_number', auth()->user()->phone_number) }}</p>
                </div>
            </div>
        </div>
        <!-- Profile Edit Section (right section) -->
        <div class="bg-white shadow-xl rounded-lg p-6 space-y-6 border border-gray-200 lg:col-span-2">
            <h3 class="text-2xl font-semibold text-gray-800">Edit Profile</h3>
            <form action="{{route('ProfileUpdate')}}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                 <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-600">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" 
                           class="w-full mt-1 p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                           required>
                </div>
                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                           class="w-full mt-1 p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                           required>
                </div>

                <!-- Phone Number -->
                <div class="mb-4">
                    <label for="phone_number" class="block text-sm font-medium text-gray-600">Phone Number</label>
                    <input type="number" id="phone_number" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}" 
                           class="w-full mt-1 p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                           required>
                </div>
                <div class="mb-4">
                    <label for="avatar" class="block text-sm font-medium text-gray-600">Profile Image</label>
                    <input type="file" id="avatar" name="avatar" class="w-full mt-1 p-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <!-- Submit Button -->
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-md shadow-md hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
