<nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('posts.index') }}" class="text-2xl font-bold text-gray-900 hover:text-gray-700">
                    📝 Blog
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('posts.index') }}"
                    class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                    Home
                </a>
                
                @auth
                    <!-- Authenticated User Menu -->
                    <div class="flex items-center space-x-4 ml-4 pl-4 border-l border-gray-200">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-sm font-semibold text-indigo-700">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                        </div>
                        
                        <a href="/admin"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Panel Filament
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 inline">
                            @csrf
                            <button type="submit"
                                class="text-red-600 hover:bg-red-50 hover:text-red-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                Wyloguj się
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Guest Menu -->
                    <div class="flex items-center space-x-4 ml-4 pl-4 border-l border-gray-200">
                        <a href="{{ route('login') }}"
                            class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Zaloguj się
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Zarejestruj się
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
