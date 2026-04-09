<?php

$content = file_get_contents('resources/views/posts/show.blade.php');

$startMarker = '        <!-- Comments Section -->';
$endMarker = '        <!-- Related Posts -->';

$startIndex = strpos($content, $startMarker);
$endIndex = strpos($content, $endMarker);

if ($startIndex !== false && $endIndex !== false) {
    $before = substr($content, 0, $startIndex);
    $after = substr($content, $endIndex);

    $newComments = <<<'BLADE'
        <!-- Comments Section -->
        <section class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                Komentarze ({{ $post->comments->whereNull('parent_id')->reduce(fn($sum, $c) => $sum + 1 + $c->replies->count(), 0) }})
            </h2>

            @auth
            <!-- Comment Form -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Dodaj komentarz</h3>
                <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Komentarz *
                        </label>
                        <textarea id="content" name="content" required rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                            placeholder="Podziel się swoimi przemyśleniami..."></textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                            Opublikuj komentarz
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="mb-8 pb-8 border-b border-gray-200 text-gray-700">
                <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Zaloguj się</a>, aby dodać komentarz.
            </div>
            @endauth

            <!-- Comments List -->
            <div class="space-y-6">
                @foreach ($post->comments as $comment)
                    <!-- Top-level Comment -->
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-semibold text-gray-900">{{ $comment->user->name }}</h4>
                                        <span class="px-2 py-0.5 bg-gray-200 text-gray-800 text-xs rounded-full">{{ $comment->user->role->value ?? 'User' }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500" title="{{ $comment->created_at }}">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-700 leading-relaxed">{{ $comment->content }}</p>
                                
                                @auth
                                <div class="mt-3" x-data="{ open: false }">
                                    <button @click="open = !open" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                        Odpowiedz
                                    </button>
                                    
                                    <div x-show="open" class="mt-4" style="display: none;">
                                        <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <textarea name="content" required rows="2"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"
                                                placeholder="Napisz odpowiedź..."></textarea>
                                            <div class="flex gap-2">
                                                <button type="submit" class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-indigo-700">Odpowiedz</button>
                                                <button type="button" @click="open = false" class="bg-gray-200 text-gray-800 px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-gray-300">Anuluj</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endauth
                            </div>

                            <!-- Replies -->
                            @if ($comment->replies->count() > 0)
                                <div class="mt-4 space-y-4">
                                    @foreach ($comment->replies as $reply)
                                        <div class="ml-8 flex gap-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                    {{ strtoupper(substr($reply->user->name, 0, 2)) }}
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-100">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <div class="flex items-center gap-2">
                                                            <h4 class="font-semibold text-gray-900">{{ $reply->user->name }}</h4>
                                                            <span class="px-2 py-0.5 bg-indigo-600 text-white text-xs rounded-full">{{ $reply->user->role->value ?? 'User' }}</span>
                                                        </div>
                                                        <span class="text-sm text-gray-500" title="{{ $reply->created_at }}">{{ $reply->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-gray-700 leading-relaxed">{{ $reply->content }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

BLADE;

    file_put_contents('resources/views/posts/show.blade.php', $before.$newComments.$after);
    echo "Replaced comments.\n";
} else {
    echo "Could not find markers.\n";
}
