@extends('frontend.layouts.app')
@section('content')
  <!-- Banner Section -->
  <div class="relative h-64 bg-cover bg-center flex items-center justify-center text-center text-white"
       style="background-image: url('https://source.unsplash.com/1600x400/?booking,abstract');">
    <div class="bg-black/50 w-full h-full absolute top-0 left-0 z-0"></div>
    <h1 class="z-10 text-4xl md:text-5xl font-bold">{{$vendorname->name}}</h1>
  </div>
  <!-- Booking Grid -->
  <div class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold mb-8 text-gray-900 dark:text-white">Bookings</h2>
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
        <div class="col-span-full flex justify-center py-20">
          <div class="p-8 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all max-w-md text-center dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex flex-col items-center">
              <div class="bg-blue-100 p-6 rounded-full mb-6">
                <!-- Calendar X icon -->
                <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2zM15 11l-6 6m0-6l6 6" />
                </svg>
              </div>
              <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-2">Oops...</h3>
              <p class="text-gray-500 dark:text-gray-400 mb-4">
                It seems like there are no Bookings created, at this moment.
              </p>
            </div>
          </div>
        </div>
      @endforelse
    </div>
  </div>
@endsection
