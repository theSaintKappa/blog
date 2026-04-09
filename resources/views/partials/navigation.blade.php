<nav class="sticky top-0 w-full z-50 bg-white/70 dark:bg-zinc-950/70 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-800 transition-colors duration-300">
    <div class="max-w-5xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="flex justify-between h-20 items-center">
            <!-- Brand -->
            <a href="{{ route('posts.index') }}" class="text-xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50 uppercase flex items-center space-x-2">
                <span class="w-4 h-4 bg-zinc-900 dark:bg-zinc-50 inline-block rotate-45 rounded-sm"></span>
                <span>Vibe <span class="text-zinc-400 dark:text-zinc-500">Blog</span></span>
            </a>

            <!-- Right side -->
            <div class="flex items-center space-x-6">
                <!-- Auth/Profile -->
                <div class="flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button type="button" @click="darkMode = !darkMode" class="p-2 rounded-full text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800 dark:text-zinc-400 transition">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

                    @auth
                        <div class="flex items-center space-x-4">
                            <div class="hidden md:flex items-center space-x-2">
                                <span class="w-8 h-8 flex items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800 text-xs font-bold text-zinc-900 dark:text-zinc-50">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </span>
                                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ auth()->user()->name }}</span>
                            </div>
                            <a href="/admin" class="text-sm font-medium px-4 py-2 bg-zinc-900 hover:bg-zinc-800 text-white dark:bg-zinc-50 dark:text-zinc-900 dark:hover:bg-zinc-200 rounded-lg transition-colors">
                                Panel
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 inline">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition-colors">
                                    Wyloguj
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-50 transition-colors">
                            Zaloguj się
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-medium px-4 py-2 bg-zinc-900 hover:bg-zinc-800 text-white dark:bg-zinc-50 dark:text-zinc-900 dark:hover:bg-zinc-200 rounded-lg transition-colors">
                            Rejestracja
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>
