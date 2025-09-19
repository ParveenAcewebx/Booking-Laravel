<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ get_setting('site_title', config('app.name', 'Universal Booking Solution')) }}</title>
  <link rel="icon" href="{{ get_setting('favicon') ? asset('storage/' . get_setting('favicon')) : asset('assets/images/favicon.ico') }}" type="image/x-icon">

  <!-- Tailwind CSS & Font Awesome -->
  <script src="https://cdn.tailwindcss.com"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
  <!-- jQuery & jQuery UI -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <!-- FormBuilder CSS & JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/formBuilder/dist/form-builder.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <!-- Flatpickr JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/formBuilder/dist/form-builder.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/frontend/custom.css') }}?v={{ time() }}">
  <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

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
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
  <!-- Optional Libraries -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  
  <script src="https://cdn.jsdelivr.net/npm/formBuilder/dist/form-builder.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script src="https://unpkg.com/preline@latest/dist/preline.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src= "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <!-- Bootstrap 4 JS -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <!-- Custom JS -->
  <script src="{{ asset('assets/js/frontend/custom.js') }}?v={{ time() }}"></script>
  <script src="{{ asset('assets/js/frontend/calendar.js') }}?v={{ time() }}"></script>
  <script src="{{ asset('assets/js/frontend/vendor/vendor.js') }}?v={{ time() }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
  @stack('scripts')

</body>
</html>
