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
                  <label for="avatar" class="block text-sm font-medium text-gray-600">Profile Image</label>
                  <input type="file" id="avatar" name="avatar" 
                     class="w-full mt-1 p-4 border rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500">
               </div>
               <div class="flex justify-end mt-6">
                  <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-md shadow-md hover:bg-indigo-700">
                  Save Changes
                  </button>
               </div>
            </form>
         </div>
         <!-- Bookings Tab -->