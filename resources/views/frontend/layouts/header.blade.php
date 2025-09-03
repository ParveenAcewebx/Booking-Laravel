  <header class="sticky top-0 z-50 bg-gray-900 backdrop-blur-lg border-b dark:bg-neutral-900/80 dark:border-neutral-700">
    <nav class="max-w-7xl mx-auto flex items-center justify-between p-4 md:px-6">
      <!-- Logo -->
      <a href="{{ route('home') }}" class="flex items-center space-x-3 text-xl font-semibold text-black dark:text-white">
        @php
            $logo = get_setting('website_logo');
            $logoStoragePath = 'public/' . $logo; // Ensure this matches your disk config
        @endphp

        @if ($logo && Storage::exists($logoStoragePath))
            <img src="{{ asset('storage/' . $logo) }}" alt="MyBrand Logo" class="h-14 w-auto">
        @else
            <img src="{{ asset('assets/images/no-image-available.png') }}" alt="No Image" class="h-14 w-auto">
        @endif
      </a>
      <!-- Desktop Menu -->
      <div class="hidden md:flex space-x-6">
        <a href="{{ route('home') }}"
          class="relative font-medium text-white
                {{ request()->routeIs('home') ? 'before:absolute before:-bottom-1.5 before:w-full before:h-1 before:bg-yellow-400' : '' }}">
          Home
        </a>
        <a href="{{ route('booking.listing') }}"
          class="relative font-medium text-white
                {{ request()->routeIs('booking.listing') ? 'before:absolute before:-bottom-1.5 before:w-full before:h-1 before:bg-yellow-400' : '' }}">
          Bookings
        </a>
        @if($isVendorStaff)
            <a href="{{ url('/vendor-information') }}" class="relative font-medium text-white">
                Vendorinfo
            </a>
        @endif
      </div>
      <!-- Buttons & Mobile Toggle -->
      <div class="flex items-center gap-2 relative" x-data="{ open: false }">
        @auth
          <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/images/no-image-available.png') }}"
              alt="{{ auth()->user()->name }}"
              onerror="this.onerror=null;this.src='{{ asset('assets/images/no-image-available.png') }}';"
              class="h-8 w-8 rounded-full object-cover border border-gray-300">
            <span class="text-sm font-medium text-white">
              {{ auth()->user()->name }}
            </span>
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          <!-- Dropdown -->
         <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95"
        class="absolute right-0 mt-40 w-40 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
            @auth
        <!-- Dropdown items -->
        <ul class="text-gray-700">
            <!-- Profile Link -->
            <li class="hover:bg-blue-100 rounded-t-lg transition duration-200">
               @if(auth()->user()->hasRole('Administrator')) 
                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm font-medium">Profile</a>
                @else
                 <a href="{{ route('Userprofile') }}" class="block px-4 py-2 text-sm font-medium">Profile</a>
                @endif
            </li>
            

          @if(auth()->user()->hasRole('Administrator')) 
          <li class="hover:bg-blue-100 transition duration-200">
              <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm font-medium">Dashboard</a>
          </li>
          @endif
               @php
                $isImpersonating = session()->has('impersonate_original_user') || Cookie::get('impersonate_original_user');
                $loginUser = null;
                if ($isImpersonating) {
                    $loginUser = \App\Models\User::find(session('impersonate_original_user') ?? Cookie::get('impersonate_original_user'));
                }
            @endphp

            <!-- Switch Back Button (if impersonating) -->
            @if($isImpersonating && $loginUser)
            <li>
                <form method="POST" action="{{ route('user.switch.back') }}" class="m-0 hover:bg-red-100">
                    @csrf
                    <button type="submit" class="flex front-switch w-full px-4 py-2 text-sm font-medium text-red-600">
                        Switch Back
                    </button>
                </form>
            </li>
            @endif
         
          @endauth            
            <!-- Logout Link -->
            <li class="hover:bg-red-100 rounded-b-lg transition duration-200">
                <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm font-medium text-red-600">Logout</a>
            </li>
          
        </ul>
          </div>
        @else
        
          <!-- Show Sign in button if guest -->
          <a href="{{ route('login') }}"
            class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-sm font-semibold rounded-lg">
            Sign in
          </a>
        @endauth
        <button type="button" class="md:hidden hs-collapse-toggle p-2 rounded-lg border border-gray-300"
          data-hs-collapse="#mobile-menu" aria-controls="mobile-menu" aria-expanded="false">
          <svg class="hs-collapse-open:hidden w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <line x1="3" x2="21" y1="6" y2="6" />
            <line x1="3" x2="21" y1="12" y2="12" />
            <line x1="3" x2="21" y1="18" y2="18" />
          </svg>
          <svg class="hs-collapse-open:block hidden w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
          </svg>
        </button>
      </div>
    </nav>
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hs-collapse hidden md:hidden px-4 pb-4">
      <a href="{{ route('home') }}"
        class="block py-2 font-medium {{ request()->routeIs('home') ? 'text-black dark:text-white' : 'text-gray-600 dark:text-gray-300' }}">
        Home
      </a>
      <a href="{{ route('home') }}"
        class="block py-2 font-medium {{ request()->routeIs('home') ? 'text-black dark:text-white' : 'text-gray-600 dark:text-gray-300' }}">
        Bookings
      </a>
      <a href="#" class="block py-2 text-gray-600 dark:text-gray-300">Checkout</a>
    </div>
  </header>