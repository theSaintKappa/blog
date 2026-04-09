<?php
$html = <<<'HTML'
<!DOCTYPE html>
<html lang="pl" class="h-full antialiased selection:bg-black selection:text-white dark:selection:bg-white dark:selection:text-black">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body x-data="{ 
          darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) 
      }" 
      x-init="$watch('darkMode', val => {
          localStorage.setItem('theme', val ? 'dark' : 'light');
          val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark');
      })" class="font-sans text-zinc-900 dark:text-zinc-50 antialiased h-full bg-zinc-50 dark:bg-zinc-950 transition-colors duration-300">
    
    @include('partials.navigation')

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-24 sm:pt-32 pb-12">
        <div>
            <a href="/" class="text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-zinc-50 uppercase flex items-center space-x-3 mb-4">
                <span class="w-6 h-6 bg-zinc-900 dark:bg-zinc-50 inline-block rotate-45 rounded-sm"></span>
                <span>Twój <span class="text-zinc-400 dark:text-zinc-500">Blog</span></span>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white dark:bg-zinc-900 ring-1 ring-zinc-200 dark:ring-zinc-800 shadow-xl overflow-hidden sm:rounded-2xl">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
HTML;
file_put_contents('resources/views/layouts/guest.blade.php', $html);
echo "Updated guest layout\n";
