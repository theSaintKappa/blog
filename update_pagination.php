<?php
$html = <<<'HTML'
<div>
    @if ($paginator->hasPages())
        @php(isset($this) ? $this->getPageName() : $paginator->getPageName())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="flex justify-between flex-1 sm:hidden">
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-zinc-400 dark:text-zinc-600 bg-zinc-50 dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800 cursor-not-allowed leading-5 rounded-xl">
                        &laquo; Poprzednia
                    </span>
                @else
                    <button wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-zinc-900 dark:text-zinc-50 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 leading-5 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 transition ease-in-out duration-150">
                        &laquo; Poprzednia
                    </button>
                @endif

                @if ($paginator->hasMorePages())
                    <button wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-bold text-zinc-900 dark:text-zinc-50 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 leading-5 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 transition ease-in-out duration-150">
                        Następna &raquo;
                    </button>
                @else
                    <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-bold text-zinc-400 dark:text-zinc-600 bg-zinc-50 dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800 cursor-not-allowed leading-5 rounded-xl">
                        Następna &raquo;
                    </span>
                @endif
            </div>

            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-5 font-medium">
                        Pokazano od
                        <span class="font-extrabold text-zinc-900 dark:text-zinc-50">{{ $paginator->firstItem() }}</span>
                        do
                        <span class="font-extrabold text-zinc-900 dark:text-zinc-50">{{ $paginator->lastItem() }}</span>
                        z
                        <span class="font-extrabold text-zinc-900 dark:text-zinc-50">{{ $paginator->total() }}</span>
                        wyników
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex rounded-xl shadow-sm">
                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                            <span aria-disabled="true" aria-label="Poprzednia strona">
                                <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-zinc-400 dark:text-zinc-600 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 cursor-not-allowed rounded-l-xl leading-5" aria-hidden="true">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        @else
                            <button wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="prev" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-zinc-50 dark:hover:bg-zinc-800 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-l-xl leading-5 focus:z-10 focus:outline-none focus:ring-2 focus:ring-zinc-900 transition ease-in-out duration-150" aria-label="Poprzednia strona">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-bold text-zinc-700 dark:text-zinc-400 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 cursor-not-allowed leading-5">{{ $element }}</span>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <span aria-current="page">
                                            <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-extrabold text-white bg-zinc-900 dark:bg-zinc-50 dark:text-zinc-900 border border-zinc-900 dark:border-zinc-50 tracking-tight cursor-default leading-5 z-10">{{ $page }}</span>
                                        </span>
                                    @else
                                        <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-bold text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-zinc-50 dark:hover:bg-zinc-800 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 leading-5 focus:z-10 focus:outline-none focus:ring-2 focus:ring-zinc-900 transition ease-in-out duration-150" aria-label="Idź do strony {{ $page }}">
                                            {{ $page }}
                                        </button>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            <button wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="next" class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-zinc-50 dark:hover:bg-zinc-800 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-r-xl leading-5 focus:z-10 focus:outline-none focus:ring-2 focus:ring-zinc-900 transition ease-in-out duration-150" aria-label="Następna strona">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @else
                            <span aria-disabled="true" aria-label="Następna strona">
                                <span class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-zinc-400 dark:text-zinc-600 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 cursor-not-allowed rounded-r-xl leading-5" aria-hidden="true">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
HTML;
file_put_contents('resources/views/vendor/livewire/tailwind.blade.php', $html);
echo "Updated pagination\n";
