<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
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

    #[Url]
    public string $tag = '';

    #[Url]
    public string $author = '';

    #[Url]
    public string $sort = 'newest';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedTag()
    {
        $this->resetPage();
    }

    public function updatedAuthor()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->tag = '';
        $this->author = '';
        $this->sort = 'newest';
        $this->resetPage();
    }

    public function with(): array
    {
        $posts = Post::query()
            ->when(auth()->user()?->role !== \App\Enums\Role::Admin, function ($query) {
                $query->where('is_published', true);
            })
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
            ->when($this->tag, function ($query, $tag) {
                $query->whereHas('tags', function ($q) use ($tag) {
                    $q->where('slug', $tag);
                });
            })
            ->when($this->author, function ($query, $author) {
                $query->where('user_id', $author);
            })
            ->when($this->sort === 'oldest', function ($query) {
                $query->oldest();
            }, function ($query) {
                $query->latest();
            })
            ->paginate(12);

        return [
            'posts' => $posts,
            'categories' => Category::all(),
            'tags' => Tag::all(),
            'authors' => User::whereHas('posts')->get(),
        ];
    }
};
?>

<div>
    <!-- Filters/Search Bar -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4 relative" x-data="{ openFilters: false }">
        <div class="flex-1 flex gap-2">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Szukaj postów..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
        
        <div class="relative">
            <button @click="openFilters = !openFilters" type="button" class="w-full sm:w-auto inline-flex items-center justify-between px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Filtry zaawansowane
                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div x-show="openFilters" 
                @click.away="openFilters = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 z-50 mt-2 w-72 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                style="display: none;">
                
                <div class="p-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategoria</label>
                        <select wire:model.live="category" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Wszystkie kategorie</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tagi</label>
                        <select wire:model.live="tag" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Wszystkie tagi</option>
                            @foreach ($tags as $t)
                                <option value="{{ $t->slug }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
                        <select wire:model.live="author" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Wszyscy autorzy</option>
                            @foreach ($authors as $usr)
                                <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sortowanie</label>
                        <select wire:model.live="sort" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="newest">Najnowsze</option>
                            <option value="oldest">Najstarsze</option>
                        </select>
                    </div>

                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <button wire:click="clearFilters" @click="openFilters = false" type="button" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Wyczyść filtry
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
