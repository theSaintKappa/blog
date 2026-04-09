<!DOCTYPE html>
<html lang="pl" class="h-full antialiased selection:bg-black selection:text-white dark:selection:bg-white dark:selection:text-black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Polski Blog IT' }}</title>

    @vite(['resources/css/app.css'])
    
    <script>
        // Prevent FOUC and handle Livewire navigation
        function updateTheme() {
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        updateTheme();
        document.addEventListener('livewire:navigated', updateTheme);
    </script>
</head>

<body x-data="{ 
          darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) 
      }" 
      x-init="
          $watch('darkMode', val => {
              localStorage.setItem('theme', val ? 'dark' : 'light');
              val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark');
          })
      "
      class="h-full bg-white text-zinc-900 dark:bg-zinc-950 dark:text-zinc-50 transition-colors duration-300">
      
      <div class="flex flex-col min-h-screen">
        @include('partials.navigation')
        <div class="w-full max-w-5xl mx-auto px-6 sm:px-8 lg:px-12">
            {{ $slot }}
        </div>
        @include('partials.footer')
    </div>

    @vite(['resources/js/app.js'])
</body>
</html>
