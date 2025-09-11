@extends('frontend.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="{ tab: 'info' }">
<div class="mb-8 text-center">
    <h1 class="text-3xl font-bold text-gray-800">Vendor Dashboard</h1>
    <p class="text-gray-600 mt-2">Manage services, staff, and bookings in one place</p>
</div>

<div class="container mx-auto  flex gap-6">
    <x-vendor-sidebar />
    <div class="w-3/4 bg-white shadow rounded-2xl p-6">
                    <div class="flex border-b mb-6 space-x-4">
                <button type="button"
                    :class="tab === 'info' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500'"
                    class="pb-2 text-sm font-semibold focus:outline-none" @click="tab = 'info'">Info</button>

                <button type="button"
                    :class="tab === 'pricing' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500'"
                    class="pb-2 text-sm font-semibold focus:outline-none" @click="tab = 'pricing'">Pricing</button>
                <button type="button"
                    :class="tab === 'gallery' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500'"
                    class="pb-2 text-sm font-semibold focus:outline-none" @click="tab = 'gallery'">Gallery</button>
                    <button type="setting"
                    :class="tab === 'setting' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500'"
                    class="pb-2 text-sm font-semibold focus:outline-none" @click="tab = 'setting'">Setting</button>
            </div>
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Service</h2>
        <form action="{{ route('vendor.services.update', $servicedata[0]->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
        <div x-show="tab === 'info'" x-transition>
            <!-- Service Name -->
            <div class="form-group">
                <label class="block text-sm font-medium text-gray-600">Service Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $servicedata[0]->name) }}"
                       class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500 {{ $errors->has('name') ? 'border-red-500' : '' }}">
            @error('name')
                <div class="text-red-500 mt-2 error_message">{{ $message }}</div>
            @enderror
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
              <div>
                <label class="block text-sm font-medium text-gray-600">Status</label>
                <select name="status" class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500">
                    <option value="1" {{ old('status', $servicedata[0]->status) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $servicedata[0]->status) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
          <div x-show="tab === 'pricing'" x-transition>
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
</div>
            <!-- Status -->
          
                <div x-show="tab === 'gallery'" x-transition>
                            <!-- Featured Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Featured Image</label>
                                <input type="file" name="thumbnail" class="w-full mt-1 p-2 border rounded-md" accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
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
                            <input type="file" name="gallery[]" id="gallery-input" multiple class="w-full mt-1 p-2 border rounded-md" accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">

                            @if($servicedata[0]->gallery)
                                <div class="flex gap-2 mt-2 flex-wrap" id="gallery-preview">
                                    @foreach(json_decode($servicedata[0]->gallery) as $key => $img)
                                        <div class="relative w-16 h-16 group" data-img="{{ str_replace('storage/', '', $img) }}">
                                            <img src="{{ asset('storage/'.$img) }}" class="w-16 h-16 rounded shadow object-cover">
                                            
                                            {{-- Delete Button --}}
                                        <button type="button"
                                            class="absolute top-0 right-0 bg-red-600 text-white text-xs px-1 rounded-full"
                                            onclick="deleteGalleryImage(this)">
                                            ✕
                                        </button>
                                            {{-- Hidden input to keep existing images --}}
                                            <input type="hidden" name="existing_gallery[]" value="{{ str_replace('storage/', '', $img) }}">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                </div>
            <div x-show="tab === 'setting'" x-transition>
                <div class="tab-pane active" id="settings" role="tabpanel">
                    <div class="form-group mt-4">
                        <label class="block text-sm font-medium text-gray-600">Default Appointment Status</label>
                        <select name="appointment_status" class="w-full mt-1 p-2 border rounded-md">
                            <option value="1" {{ old('appointment_status', $servicedata[0]->appointment_status) == 1 ? 'selected' : '' }}>Approved</option>
                            <option value="0" {{ old('appointment_status', $servicedata[0]->appointment_status) == 0 ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>

                    <div class="form-group mt-4">
                        <label class="block text-sm font-medium text-gray-600">Minimum Time Required Before Canceling</label>
                        <div class="flex ">
                            <div class="w-3/4 pr-1">
                                <select name="cancelling_unit" class="w-full mt-1 p-2 border rounded-md" id="cancelling_unit">
                                    <option value="hours" {{ old('cancelling_unit', $servicedata[0]->cancelling_unit) == 'hours' ? 'selected' : '' }}>Hours</option>
                                    <option value="days" {{ old('cancelling_unit', $servicedata[0]->cancelling_unit) == 'days' ? 'selected' : '' }}>Days</option>
                                </select>
                            </div>

                            <div class="w-3/4 pr-0 ">
                                <select name="cancelling_value" class="w-full mt-1 p-2 border rounded-md" id="cancelling_value">
                                    @for($i=1; $i <= 60; $i++)
                                        <option value="{{ $i }}" {{ old('cancelling_value', $servicedata[0]->cancelling_value) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <input type="hidden" id="cancel_value" value="{{ old('cancelling_value', $servicedata[0]->cancelling_value) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <label class="block text-sm font-medium text-gray-600">Redirect URL After Booking</label>
                        <input type="url" name="redirect_url" class="w-full mt-1 p-2 border rounded-md" value="{{ old('redirect_url', $servicedata[0]->redirect_url) }}" placeholder="https://example.com" pattern="https?://.*" title="Please enter a valid URL starting with http:// or https://">
                    </div>

                    <div class="form-group mt-4">
                        <label class="block text-sm font-medium text-gray-600">Payment Mode</label>
                        <select name="payment_mode" class="w-full mt-1 p-2 border rounded-md" id="payment_mode">
                            <option value="on_site" {{ old('payment_mode', $servicedata[0]->payment_mode) == 'on_site' ? 'selected' : '' }}>On Site</option>
                            <option value="stripe" {{ old('payment_mode', $servicedata[0]->payment_mode) == 'stripe' ? 'selected' : '' }}>Stripe</option>
                        </select>
                    </div>

                    <div class="stripe-options {{ old('payment_mode', $servicedata[0]->payment_mode) == 'stripe' ? '' : 'hidden' }}">
                        <div class="custom-control custom-radio block text-sm font-medium text-gray-600">
                            <input type="radio" id="stripeDefault" name="payment_account" value="default" {{ old('payment_account', $servicedata[0]->payment_account) == 'default' ? 'checked' : '' }}>
                            <label for="stripeDefault">Use Default Stripe Account</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="stripeCustom" name="payment_account" value="custom" {{ old('payment_account', $servicedata[0]->payment_account) == 'custom' ? 'checked' : '' }}>
                            <label for="stripeCustom">Use Different Stripe Account</label>
                        </div>

                        <div class="stripe-credentials mt-3 {{ old('payment_account', $servicedata[0]->payment_account) == 'custom' ? '' : 'hidden' }}">
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" id="payment__is_live" name="payment__is_live" value="1" {{ old('payment__is_live', $servicedata[0]->payment__is_live) ? 'checked' : '' }}>
                                <label for="payment__is_live">Live Mode</label>
                            </div>

                            <div class="stripe-test {{ old('payment__is_live', $servicedata[0]->payment__is_live) ? 'hidden' : '' }}">
                                <div class="form-group">
                                    <label for="stripe_test_site_key">Test Site Key</label>
                                    <input type="text" name="stripe_test_site_key" id="stripe_test_site_key" class="w-full mt-1 p-2 border rounded-md" value="{{ old('stripe_test_site_key', $servicedata[0]->stripe_test_site_key) }}">
                                </div>
                                <div class="form-group">
                                    <label for="stripe_test_secret_key">Test Secret Key</label>
                                    <input type="text" name="stripe_test_secret_key" id="stripe_test_secret_key" class="w-full mt-1 p-2 border rounded-md" value="{{ old('stripe_test_secret_key', $servicedata[0]->stripe_test_secret_key) }}">
                                </div>
                            </div>

                            <div class="stripe-live {{ old('payment__is_live', $servicedata[0]->payment__is_live) ? '' : 'hidden' }}">
                                <div class="form-group">
                                    <label for="stripe_live_site_key">Live Site Key</label>
                                    <input type="text" name="stripe_live_site_key" id="stripe_live_site_key" class="w-full mt-1 p-2 border rounded-md" value="{{ old('stripe_live_site_key', $servicedata[0]->stripe_live_site_key) }}">
                                </div>
                                <div class="form-group">
                                    <label for="stripe_live_secret_key">Live Secret Key</label>
                                    <input type="text" name="stripe_live_secret_key" id="stripe_live_secret_key" class="w-full mt-1 p-2 border rounded-md" value="{{ old('stripe_live_secret_key', $servicedata[0]->stripe_live_secret_key) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
