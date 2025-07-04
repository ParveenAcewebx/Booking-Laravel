@extends('frontend.layouts.app')

@section('content')
  <!-- Banner Section -->
  <div class="relative h-64 bg-cover bg-center flex items-center justify-center text-center text-white"
       style="background-image: url('https://source.unsplash.com/1600x400/?booking,abstract');">
    <div class="bg-black/50 w-full h-full absolute top-0 left-0 z-0"></div>
    <h1 class="z-10 text-4xl md:text-5xl font-bold">Bookings</h1>
  </div>

  <!-- Booking Grid -->
  <div class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold mb-8 text-gray-900 dark:text-white">All Bookings</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      @forelse ($bookings as $booking)
        <a href="{{ route('form.show', $booking->slug ?? '') }}"
           class="block p-5 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all dark:bg-neutral-800 dark:border-neutral-700">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ $booking->template_name ?? 'Untitled Booking' }}
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                ID: {{ $booking->id }}
              </p>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path d="M9 18l6-6-6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </a>
      @empty
        <p class="text-gray-500 dark:text-gray-400">No bookings found.</p>
      @endforelse
    </div>
  </div>
@endsection
