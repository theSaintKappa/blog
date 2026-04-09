<x-layout :title="$post->title . ' | Twój Blog'">
    <!-- Main Content -->
    <main class="max-w-4xl mx-auto py-8">

        <!-- Post Article -->
        <article class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm ring-1 ring-zinc-200 dark:ring-zinc-800 overflow-hidden mb-12">
            <!-- Featured Image -->
            <div class="aspect-[21/9] w-full bg-zinc-100 dark:bg-zinc-950 relative overflow-hidden">
                @if($post->photo)
                    <img src="{{ $post->photo }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-zinc-200 dark:bg-zinc-800">
                        <span class="text-8xl grayscale opacity-30">📰</span>
                    </div>
                @endif
                <div class="absolute inset-0 ring-1 ring-inset ring-black/5 dark:ring-white/10"></div>
            </div>

            <!-- Post Content -->
            <div class="p-8 md:p-12">
                <!-- Meta Info -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 pb-8 border-b border-zinc-100 dark:border-zinc-800">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center text-xl font-bold text-zinc-800 dark:text-zinc-200">
                            {{ strtoupper(substr($post->user?->name ?? $post->author ?? 'A', 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-bold text-zinc-900 dark:text-zinc-50">{{ $post->user?->name ?? $post->author ?? 'Anonim' }}</p>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ $post->created_at->isoFormat('D MMMM YYYY') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        @if ($post->is_published)
                            <span class="px-4 py-1.5 bg-zinc-900 dark:bg-zinc-50 text-white dark:text-zinc-900 text-xs font-bold uppercase tracking-wider rounded-full">
                                Opublikowany
                            </span>
                        @else
                            <span class="px-4 py-1.5 bg-zinc-200 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 text-xs font-bold uppercase tracking-wider rounded-full">
                                Szkic
                            </span>
                        @endif
                        
                        <div class="flex items-center gap-2">
                            @can('update', $post)
                                @if (!$post->is_published)
                                <form action="{{ route('posts.publish', $post) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-1.5 bg-emerald-500/10 text-emerald-600 hover:bg-emerald-500/20 text-xs font-bold uppercase tracking-wider rounded-full transition-colors">
                                        Publikuj
                                    </button>
                                </form>
                                @endif
                                <a href="{{ App\Filament\Resources\Posts\PostResource::getUrl('edit', ['record' => $post]) ?? '#' }}" class="px-4 py-1.5 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-500/20 text-xs font-bold uppercase tracking-wider rounded-full transition-colors">
                                    Edytuj
                                </a>
                            @endcan
                            @can('delete', $post)
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Czy na pewno chcesz usunąć ten wpis?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-1.5 bg-red-500/10 text-red-600 hover:bg-red-500/20 text-xs font-bold uppercase tracking-wider rounded-full transition-colors">
                                        Usuń
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>

                <!-- Title -->
                <h1 class="text-4xl md:text-5xl font-extrabold text-zinc-900 dark:text-zinc-50 mb-6 leading-tight tracking-tight">
                    {{ $post->title }}
                </h1>

                @if ($post->lead)
                    <!-- Lead -->
                    <p class="text-xl md:text-2xl text-zinc-500 dark:text-zinc-400 mb-10 leading-relaxed font-medium">
                        {{ $post->lead }}
                    </p>
                @endif

                <!-- Content -->
                <div class="prose prose-lg dark:prose-invert prose-zinc max-w-none">
                    {!! $post->content !!}
                </div>

                <!-- Tags -->
                @if ($post->tags && $post->tags->isNotEmpty())
                    <div class="mt-12 pt-8 border-t border-zinc-100 dark:border-zinc-800">
                        <div class="flex flex-wrap gap-2">
                            @foreach ($post->tags as $tag)
                                <span class="px-4 py-2 bg-zinc-100 dark:bg-zinc-800/50 text-zinc-700 dark:text-zinc-300 text-sm font-medium rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-800 transition-colors cursor-pointer">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Social Share placeholder -->
                <div class="mt-12 flex items-center gap-4">
                    <span class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Udostępnij</span>
                    <div class="flex gap-2">
                        <button class="w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 transition-colors">
                            X
                        </button>
                        <button class="w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 transition-colors">
                            in
                        </button>
                    </div>
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        <section class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm ring-1 ring-zinc-200 dark:ring-zinc-800 p-8 md:p-12 mb-12">
            <h2 class="text-2xl font-extrabold text-zinc-900 dark:text-zinc-50 mb-8">
                Komentarze ({{ $post->comments ? $post->comments->whereNull('parent_id')->reduce(fn($sum, $c) => $sum + 1 + $c->replies->count(), 0) : 0 }})
            </h2>

            @auth
            <!-- Comment Form -->
            <div class="mb-10 pb-10 border-b border-zinc-100 dark:border-zinc-800">
                <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <textarea id="content" name="content" required rows="3"
                            class="w-full px-5 py-4 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-zinc-50 rounded-2xl focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 outline-none transition-all placeholder:text-zinc-400 resize-none text-base"
                            placeholder="Zostaw komentarz jako {{ auth()->user()->name }}..."></textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-8 py-3 bg-zinc-900 dark:bg-zinc-50 text-white dark:text-zinc-900 rounded-xl font-bold hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors">
                            Opublikuj
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="mb-10 pb-10 border-b border-zinc-100 dark:border-zinc-800">
                <p class="text-zinc-600 dark:text-zinc-400 text-lg">
                    <a href="{{ route('login') }}" class="font-bold text-zinc-900 dark:text-zinc-50 hover:underline">Zaloguj się</a>, aby dodać komentarz.
                </p>
            </div>
            @endauth

            <!-- Comments List -->
            @if($post->comments)
            <div class="space-y-8">
                @foreach ($post->comments->whereNull('parent_id') as $comment)
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center text-sm font-bold text-zinc-900 dark:text-zinc-50">
                                {{ strtoupper(substr($comment->user?->name ?? 'A', 0, 2)) }}
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-zinc-50 dark:bg-zinc-950 rounded-2xl p-5 border border-zinc-100 dark:border-zinc-800">
                                <div class="flex items-center gap-3 mb-3">
                                    <h4 class="font-bold text-zinc-900 dark:text-zinc-50">{{ $comment->user?->name ?? 'Konto usunięte' }}</h4>
                                    <span class="px-2 py-0.5 bg-zinc-200 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 text-xs font-bold rounded-full">
                                        {{ $comment->user?->role->value ?? 'Użytkownik' }}
                                    </span>
                                    <div class="flex-grow"></div>
                                    <span class="text-sm font-medium text-zinc-400" title="{{ $comment->created_at }}">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-zinc-700 dark:text-zinc-300 leading-relaxed text-base">{{ $comment->content }}</p>
                                
                                @auth
                                <div class="mt-4" x-data="{ openReply: false }">
                                    <button @click="openReply = !openReply" class="text-sm font-bold text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-50 transition-colors">
                                        Odpowiedz
                                    </button>
                                    
                                    <div x-show="openReply" x-cloak class="mt-4">
                                        <form action="{{ route('posts.comments.store', $post) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <textarea name="content" required rows="2"
                                                class="w-full px-4 py-3 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-zinc-50 rounded-xl focus:ring-2 focus:ring-zinc-900 outline-none text-sm placeholder:text-zinc-400 mb-3"
                                                placeholder="Twoja odpowiedź..."></textarea>
                                            <div class="flex gap-2">
                                                <button type="submit" class="bg-zinc-900 dark:bg-zinc-50 text-white dark:text-zinc-900 px-5 py-2 rounded-lg text-sm font-bold hover:bg-zinc-800 transition-colors">Dodaj odpowiedź</button>
                                                <button type="button" @click="openReply = false" class="bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 px-5 py-2 rounded-lg text-sm font-bold hover:bg-zinc-200 transition-colors">Anuluj</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endauth
                            </div>

                            <!-- Replies -->
                            @if($comment->replies->count() > 0)
                                <div class="mt-4 space-y-4 pl-6 border-l-2 border-zinc-100 dark:border-zinc-800">
                                    @foreach($comment->replies as $reply)
                                        <div class="flex gap-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center text-xs font-bold text-zinc-900 dark:text-zinc-50">
                                                    {{ strtoupper(substr($reply->user?->name ?? 'A', 0, 2)) }}
                                                </div>
                                            </div>
                                            <div class="flex-1 bg-zinc-50 dark:bg-zinc-950 rounded-2xl p-5 border border-zinc-100 dark:border-zinc-800">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h4 class="font-bold text-zinc-900 dark:text-zinc-50">{{ $reply->user?->name ?? 'Konto usunięte' }}</h4>
                                                    <span class="text-sm font-medium text-zinc-400" title="{{ $reply->created_at }}">{{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-zinc-700 dark:text-zinc-300 leading-relaxed text-sm">{{ $reply->content }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </section>
    </main>
</x-layout>