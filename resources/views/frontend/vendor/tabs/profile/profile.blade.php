  <!-- Profile Tab -->
  <div x-show="tab === 'profile'">
     <h3 class="text-2xl font-semibold text-gray-800">Profile</h3>
     <form action="{{ route('ProfileUpdate') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <!-- Name -->
        <div class="mb-4">
           <label for="name" class="block text-sm font-medium text-gray-600">Name</label>
           <input type="text" id="name" name="name"
              value="{{ old('name', auth()->user()->name) }}"
              class="w-full mt-1 p-4 border rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500" required>
        </div>
        <!-- Email -->
        <div class="mb-4">
           <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
           <input type="email" id="email" name="email"
              value="{{ old('email', auth()->user()->email) }}"
              class="w-full mt-1 p-4 border rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500" required>
        </div>
        <!-- Phone -->
        <div class="mb-4">
           <label for="phone_number" class="block text-sm font-medium text-gray-600">Phone Number</label>
           <input type="number" id="phone_number" name="phone_number"
              value="{{ old('phone_number', auth()->user()->phone_number) }}"
              class="w-full mt-1 p-4 border rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500">
        </div>
        <!-- Avatar -->


        <div class="mb-4">
   <label class="block text-sm font-medium text-gray-600">Profile</label>
   <input type="file" id="feature-input" name="avatar"
      class="w-full mt-1 p-2 border rounded-md"
      accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">

   <div class="flex gap-2 mt-4 flex-wrap" id="new-feature-preview">
      @if(auth()->user()->avatar)
      <div class="relative w-24 h-24 inline-block existing-feature-wrapper">
         <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
            class="w-24 h-24 rounded shadow object-cover border">
         <button type="button"
            class="absolute top-0 right-0 bg-red-600 text-white text-xs px-1 rounded-full shadow existing-delete-btn">
            ✕
         </button>
         <input type="hidden" name="existing_thumbnail" value="{{ auth()->user()->avatar }}">
         <!-- Hidden remove flag -->
         <input type="hidden" name="remove_thumbnail" value="0" class="remove-thumbnail-flag">
      </div>
      @else
      <input type="hidden" name="remove_thumbnail" value="0" class="remove-thumbnail-flag">
      @endif
   </div>
</div>


        <div class="flex justify-end mt-6">
           <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-md shadow-md hover:bg-indigo-700">
              Save Changes
           </button>
        </div>
     </form>
  </div>
  <!-- Bookings Tab -->