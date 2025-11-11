<div class="w-1/4">
    <div class="bg-white shadow p-4 rounded-2xl">
    <ul class="space-y-2">
        @foreach([
            'vendor.dashboard.view' => 'Profile', 
            'vendor.bookings.view' => 'Bookings', 
            'vendor.services.view' => 'Services', 
            'vendor.staff.view' => 'Staff Members'
        ] as $route => $label)
            <li>
                <a href="{{ route($route) }}"
                   class="w-full block px-4 py-2 rounded-lg text-left
                          {{ Request::routeIs($route) ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700' }}">
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>
    </div>
</div>