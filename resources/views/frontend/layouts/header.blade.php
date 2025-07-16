  <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b dark:bg-neutral-900/80 dark:border-neutral-700">
    <nav class="max-w-7xl mx-auto flex items-center justify-between p-4 md:px-6">
      <!-- Logo -->
      <a href="{{ route('home') }}" class="flex items-center space-x-3 text-xl font-semibold text-black dark:text-white">
        <img src="{{ asset('assets/images/logo.png') }}" alt="MyBrand Logo" class="h-8 w-auto">
      </a>
      <!-- Desktop Menu -->
      <div class="hidden md:flex space-x-6">
        <a href="{{ route('home') }}"
          class="relative font-medium
                  {{ request()->routeIs('home') ? 'text-black dark:text-white before:absolute before:bottom-0.5 before:w-full before:h-1 before:bg-yellow-400' : 'text-gray-600 hover:text-black dark:text-gray-300 dark:hover:text-white' }}">
          Home
        </a>
        <a href="{{ route('booking.listing') }}"
          class="relative font-medium
                  {{ request()->routeIs('booking.listing') ? 'text-black dark:text-white before:absolute before:bottom-0.5 before:w-full before:h-1 before:bg-yellow-400' : 'text-gray-600 hover:text-black dark:text-gray-300 dark:hover:text-white' }}">
          Bookings
        </a>
      </div>
      <!-- Buttons & Mobile Toggle -->
      <div class="flex items-center gap-2 relative" x-data="{ open: false }">
        @auth
          <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/images/no-image-available.png') }}"
              alt="{{ auth()->user()->name }}"
              onerror="this.onerror=null;this.src='{{ asset('assets/images/no-image-available.png') }}';"
              class="h-8 w-8 rounded-full object-cover border border-gray-300">
            <span class="text-sm font-medium text-gray-800">
              {{ auth()->user()->name }}
            </span>
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          <!-- Dropdown -->
          <div x-show="open" @click.away="open = false"
              x-transition
              class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
            <form method="get" action="{{ route('logout') }}">
              @csrf
              <button type="submit"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                Logout
              </button>
            </form>
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