<?php

use App\Models\Category;
use App\Models\Post;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $category = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function with(): array
    {
        $posts = Post::query()
            ->where('is_published', true)
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")
                        ->orWhere('lead', 'like', "%{$search}%");
                });
            })
            ->when($this->category, function ($query, $category) {
                $query->whereHas('category', function ($q) use ($category) {
                    $q->where('slug', $category);
                });
            })
            ->latest()
            ->paginate(12);

        return [
            'posts' => $posts,
            'categories' => Category::all(),
        ];
    }
};
?>

<div>
    <!-- Filters/Search Bar -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <div class="flex-1 flex gap-2">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Szukaj postów..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
        <select wire:model.live="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            <option value="">Wszystkie kategorie</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->slug }}">
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Posts Grid -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 relative">
        <div wire:loading class="absolute inset-0 z-10 bg-white/50 backdrop-blur-sm rounded-lg flex justify-center items-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        </div>

        @forelse ($posts as $post)
            <article class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden" wire:key="post-{{ $post->id }}">
                <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                    <span class="text-6xl">{{ $post->photo ?? '📝' }}</span>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        @if ($post->is_published)
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                Opublikowany
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                Szkic
                            </span>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-indigo-600 cursor-pointer">
                        <a href="{{ route('posts.show', $post->slug) }}" wire:navigate>{{ $post->title }}</a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                        {{ $post->lead ?? Str::limit(strip_tags($post->content), 150) }}
                    </p>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-sm font-semibold">
                                {{ strtoupper(substr($post->user?->name ?? 'Anon', 0, 2)) }}
                            </div>
                            <span class="text-sm text-gray-700 font-medium">{{ $post->user?->name ?? 'Anonymous' }}</span>
                        </div>
                        <span class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">Brak postów do wyświetlenia.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</div>
