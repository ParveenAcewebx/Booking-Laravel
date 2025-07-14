<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{'Home'}}</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Tailwind Custom Config -->
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            brand: {
              yellow: '#facc15',
              dark: '#1a1a1a'
            }
          }
        }
      }
    };
  </script>

  <!-- Preline JS -->
  <script src="https://unpkg.com/preline@latest/dist/preline.js"></script>

  <!-- (Optional) Alpine.js for more interactivity -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

</body>
</html>
