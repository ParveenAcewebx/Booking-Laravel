@extends('frontend.layouts.app')

@section('content')

<div class="mb-8 text-center">
    <h1 class="text-3xl font-bold text-gray-800">Vendor Dashboard</h1>
    <p class="text-gray-600 mt-2">Manage services, staff, and bookings in one place</p>
</div>

<div class="container mx-auto px-4 py-8 flex gap-6">
    <x-vendor-sidebar />
    <div class="w-3/4 bg-white shadow rounded-2xl p-6">
        <form action="{{ route('vendor.services.update', $servicedata[0]->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Service Name -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Service Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $servicedata[0]->name) }}"
                       class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Description</label>
                <textarea name="description"
                          class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500"
                          rows="3">{{ old('description', $servicedata[0]->description) }}</textarea>
            </div>

            <!-- Duration -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Duration (minutes)</label>
                <select name="duration" class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500">
                    @for ($minutes = 30; $minutes <= 1440; $minutes += 30)
                        @php
                            $hrs = floor($minutes / 60);
                            $mins = $minutes % 60;
                            $label = ($hrs ? $hrs . ' hour' . ($hrs > 1 ? 's' : '') : '') .
                                     ($hrs && $mins ? ' ' : '') .
                                     ($mins ? $mins . ' minutes' : '');
                        @endphp
                        <option value="{{ $minutes }}" 
                            {{ old('duration', $servicedata[0]->duration) == $minutes ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Category</label>
                <select name="category" class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                            {{ old('category', $servicedata[0]->category) == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Currency & Price -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Currency</label>
                <select name="currency" class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500">
                    @foreach($currencies as $code => $currency)
                        <option value="{{ $currency['symbol'] }}" 
                            {{ old('currency', $servicedata[0]->currency) == $currency['symbol'] ? 'selected' : '' }}>
                            {{ $code }}
                        </option>
                    @endforeach
                </select>

                <label class="block text-sm font-medium text-gray-600 mt-2">Price</label>
                <input type="text" name="price" value="{{ old('price', $servicedata[0]->price) }}"
                       class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Status</label>
                <select name="status" class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500">
                    <option value="1" {{ old('status', $servicedata[0]->status) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $servicedata[0]->status) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Featured Image -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Featured Image</label>
                <input type="file" name="thumbnail" class="w-full mt-1 p-2 border rounded-md">
                @if($servicedata[0]->thumbnail)
                    <div class="relative w-20 h-20 mt-2">
                        <img src="{{ asset('storage/' . $servicedata[0]->thumbnail) }}" class="w-20 h-20 rounded shadow">
                        <input type="hidden" name="existing_thumbnail" value="{{ $servicedata[0]->thumbnail }}">
                    </div>
                @endif
            </div>

            <!-- Gallery -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Gallery Images</label>
                <input type="file" name="gallery[]" multiple class="w-full mt-1 p-2 border rounded-md">
                @if($servicedata[0]->gallery)
                    <div class="flex gap-2 mt-2 flex-wrap">
                        @foreach(json_decode($servicedata[0]->gallery) as $img)
                            <div class="relative w-16 h-16">
                                <img src="{{ asset('storage/'.$img) }}" class="w-16 h-16 rounded shadow">
                                <input type="hidden" name="existing_gallery[]" value="{{ str_replace('storage/', '', $img) }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('vendor.services.view') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update Service</button>
            </div>
        </form>
    </div>
</div>

@endsection
