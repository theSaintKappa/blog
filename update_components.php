<?php
$textInput = <<<'HTML'
@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-800 text-zinc-900 dark:text-zinc-50 rounded-xl focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent outline-none transition-all placeholder:text-zinc-400']) }}>
HTML;
file_put_contents('resources/views/components/text-input.blade.php', $textInput);

$inputLabel = <<<'HTML'
@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-bold text-zinc-700 dark:text-zinc-300 uppercase tracking-wide mb-2']) }}>
    {{ $value ?? $slot }}
</label>
HTML;
file_put_contents('resources/views/components/input-label.blade.php', $inputLabel);

$primaryButton = <<<'HTML'
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full inline-flex justify-center items-center px-6 py-3 bg-zinc-900 dark:bg-zinc-50 border border-transparent rounded-xl font-bold text-white dark:text-zinc-900 hover:bg-zinc-800 dark:hover:bg-zinc-200 focus:bg-zinc-800 dark:focus:bg-zinc-200 active:bg-zinc-900 dark:active:bg-zinc-300 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
HTML;
file_put_contents('resources/views/components/primary-button.blade.php', $primaryButton);

echo "Updated components\n";
