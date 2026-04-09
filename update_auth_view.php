<?php
$html = <<<'HTML'
<x-layout title="{{ ($isRegistering ?? false) ? 'Zarejestruj się' : 'Zaloguj się' }} | Twój Blog">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 relative overflow-hidden">
        
        <!-- Decorative blur element behind auth form -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[500px] bg-gradient-to-r from-zinc-200 to-zinc-100 dark:from-zinc-800 dark:to-zinc-900 rounded-full blur-3xl opacity-50 -z-20 pointer-events-none"></div>

        <livewire:auth-form :is-registering="$isRegistering ?? false" />
    </div>
</x-layout>
HTML;
file_put_contents('resources/views/auth/auth.blade.php', $html);
echo "Updated auth view\n";
