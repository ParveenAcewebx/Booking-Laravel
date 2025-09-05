@extends('frontend.layouts.app')

@section('content')
  @foreach (['success' => 'green', 'error' => 'red'] as $msg => $color)
        @if(session($msg))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                class="fixed top-4 right-4 bg-{{ $color }}-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 z-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    @if($msg === 'success')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    @endif
                </svg>
                <span>{{ session($msg) }}</span>
            </div>
        @endif
    @endforeach
<div class="mb-8 text-center">
    <h1 class="text-3xl font-bold text-gray-800">Vendor Dashboard</h1>
    <p class="text-gray-600 mt-2">Manage services, staff, and bookings in one place</p>
</div>

<div class="container mx-auto px-4 py-8 flex gap-6">

    <!-- Sidebar -->
    <x-vendor-sidebar />

    <!-- Main Content -->
    <div class="w-3/4 bg-white shadow rounded-2xl p-6" x-data="{ showForm: false, editService: null }">

        {{-- Services Tab --}}
        @if(Request::routeIs('vendor.services.view'))

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Services</h2>
                <button @click="showForm = !showForm; editService = null" 
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    <span x-text="showForm ? 'Cancel' : '+ Add New Service'"></span>
                </button>
            </div>

            <!-- Add/Edit Service Form -->
            <div x-show="showForm" x-transition class="mb-6">
                <form :action="editService ? '{{ url('services') }}/' + editService.id : '{{ route('vendor.services.store') }}'" 
                      method="POST" enctype="multipart/form-data" class="space-y-4 p-4 border rounded-lg bg-gray-50">
                    @csrf
                    <template x-if="editService">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <!-- Service Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Service Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="editService ? editService.name : ''"
                               class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500" required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Description</label>
                        <textarea name="description" x-model="editService ? editService.description : ''"
                                  class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500" rows="3"></textarea>
                    </div>

                    <!-- Duration -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Duration (minutes)</label>
                        <select name="duration" x-model="editService ? editService.duration : ''"
                                class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Select Duration --</option>
                            @for ($minutes = 30; $minutes <= 1440; $minutes += 30)
                                @php
                                    $hrs = floor($minutes / 60);
                                    $mins = $minutes % 60;
                                    $label = ($hrs ? $hrs . ' hour' . ($hrs > 1 ? 's' : '') : '') .
                                             ($hrs && $mins ? ' ' : '') .
                                             ($mins ? $mins . ' minutes' : '');
                                @endphp
                                <option value="{{ $minutes }}">{{ $label }}</option>
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
                                    x-bind:selected="editService ? editService.category == {{ $category->id }} : false">
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price & Currency -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Currency</label>
                        <select name="currency" x-model="editService ? editService.currency : '₹'" 
                                class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500">
                            @foreach($currencies as $code => $currency)
                                <option value="{{ $currency['symbol'] }}">{{ $code }}</option>
                            @endforeach
                        </select>

                        <label class="block text-sm font-medium text-gray-600 mt-2">Price</label>
                        <input type="text" name="price" x-model="editService ? editService.price : ''"
                               class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500"
                               inputmode="decimal"
                               pattern="^\d*\.?\d{0,3}$"
                               oninput="this.value = this.value.replace(/[^0-9.]/g,'').replace(/^(\d+(\.\d{0,3})?).*$/,'$1');"
                               placeholder="e.g., 100 or 100.50">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Status</label>
                        <select name="status" x-model="editService ? editService.status : '1'" 
                                class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <!-- Thumbnail -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Featured Image</label>
                        <input type="file" name="thumbnail" class="w-full mt-1 p-2 border rounded-md">
                        <template x-if="editService && editService.thumbnail">
                            <div class="relative w-20 h-20 mt-2">
                                <img :src="editService.thumbnail" class="w-20 h-20 rounded shadow">
                                <button type="button" @click="editService.thumbnail = null"
                                        class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center hover:bg-red-600">&times;</button>
                            </div>
                        </template>
                        <input type="hidden" name="remove_thumbnail" :value="!editService.thumbnail ? 1 : 0">
                    </div>

                    <!-- Gallery -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Gallery Images</label>
                        <input type="file" name="gallery[]" multiple class="w-full mt-1 p-2 border rounded-md">
                        <template x-if="editService && editService.gallery">
                            <div class="flex gap-2 mt-2 flex-wrap">
                                <template x-for="(img, index) in JSON.parse(editService.gallery)" :key="index">
                                    <div class="relative w-16 h-16">
                                        <img :src="img" class="w-16 h-16 rounded shadow">
                                        <button type="button"
                                                @click="editService.gallery = JSON.stringify(JSON.parse(editService.gallery).filter((_, i) => i !== index))"
                                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs hover:bg-red-600">&times;</button>
                                        <input type="hidden" name="existing_gallery[]" :value="img.replace(/^\/?storage\//,'')">
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showForm = false; editService = null"
                                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
                                x-text="editService ? 'Update Service' : 'Save Service'"></button>
                    </div>

                </form>
            </div>

            <!-- Service List -->
            <div x-show="!showForm" class="space-y-4">
                @if($servicedata && $servicedata->count() > 0)
                    @foreach($servicedata as $services_data)
                        @php 
                            $categoryObj = $categories->firstWhere('id', $services_data->category);
                        @endphp
                        <div class="space-y-4 mb-4 p-4 border rounded-lg hover:shadow">
                            <h2 class="mb-2 text-xl text-gray-800">{{ $services_data->name }}</h2>
                            <p><strong>Description:</strong> {!! $services_data->description !!}</p>
                            <p><strong>Category:</strong> {{ $categoryObj ? $categoryObj->category_name : 'Not assigned' }}</p>
                            <p><strong>Status:</strong> {{ $services_data->status == 1 ? 'Active' : 'Inactive' }}</p>
                            <p><strong>Price:</strong> {{ $services_data->currency }}{{ $services_data->price }}</p>
                            <p>
                                <strong>Duration:</strong>
                                @php $duration = $services_data->duration; @endphp
                                @if($duration < 60)
                                    {{ $duration }} minutes
                                @elseif($duration % 60 == 0)
                                    {{ $duration / 60 }} hour{{ $duration >= 120 ? 's' : '' }}
                                @else
                                    {{ intdiv($duration, 60) }} hour{{ intdiv($duration, 60) > 1 ? 's' : '' }} {{ $duration % 60 }} minutes
                                @endif
                            </p>
                            @if($services_data->thumbnail)
                                <p><strong>Featured Image:</strong></p>
                                <img src="/storage/{{ $services_data->thumbnail }}" class="w-20 h-20 rounded shadow mb-2">
                            @endif
                            @if($services_data->gallery)
                                <p><strong>Gallery:</strong></p>
                                <div class="flex gap-2 flex-wrap">
                                    @foreach(json_decode($services_data->gallery, true) as $img)
                                        <img src="/storage/{{ $img }}" class="w-16 h-16 rounded shadow">
                                    @endforeach
                                </div>
                            @endif
                            <!-- Actions -->
                            <div class="flex gap-2 mt-3">
                                <button type="button" @click="showForm = true;
                                        editService = {{ json_encode($services_data->toArray()) }};
                                        if(editService.thumbnail) editService.thumbnail = '/storage/' + editService.thumbnail;
                                        if(editService.gallery) editService.gallery = JSON.stringify(JSON.parse(editService.gallery).map(img => '/storage/' + img));"
                                        class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">Edit</button>
                                <form action="{{ route('vendor.services.destroy', $services_data->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500">No services found.</p>
                @endif
            </div>

        @endif {{-- End Services Tab --}}

    </div> {{-- End Main Content --}}
</div>
@endsection
