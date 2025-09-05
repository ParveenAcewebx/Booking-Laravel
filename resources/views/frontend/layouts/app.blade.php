<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ get_setting('site_title', config('app.name', 'Universal Booking Solution')) }}</title>
  <link rel="icon" href="{{ get_setting('favicon') ? asset('storage/' . get_setting('favicon')) : asset('assets/images/favicon.ico') }}" type="image/x-icon">

  <!-- Tailwind CSS & Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
  <!-- jQuery & jQuery UI -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

  <!-- FormBuilder CSS & JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/formBuilder/dist/form-builder.min.css">
  <script src="https://cdn.jsdelivr.net/npm/formBuilder/dist/form-builder.min.js"></script>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/frontend/custom.css') }}?v={{ time() }}">

  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            brand: { yellow: '#facc15', dark: '#1a1a1a' }
          }
        }
      }
    };
    
  </script>
</head>
<body class="bg-gray-50 text-neutral-900 dark:bg-neutral-900 dark:text-white">
  @unless($isIframe ?? false)
    @include('frontend.layouts.header')
  @endunless

  <main class="mx-auto">
    @yield('content')
  </main>

  @unless($isIframe ?? false)
    @include('frontend.layouts.footer')
  @endunless

  <!-- Optional Libraries -->
  <script src="https://unpkg.com/preline@latest/dist/preline.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src= "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <!-- Bootstrap 4 JS -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- Custom JS -->
  <script src="{{ asset('assets/js/frontend/custom.js') }}?v={{ time() }}"></script>
  <script src="{{ asset('assets/js/frontend/calendar.js') }}?v={{ time() }}"></script>

  @stack('scripts')
</body>
</html>
