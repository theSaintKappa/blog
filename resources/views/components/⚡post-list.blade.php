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

<div class="relative">
    <!-- Filters/Search Bar -->
    <div class="mb-10 flex flex-col md:flex-row gap-4 relative" x-data="{ openFilters: false }">
        <!-- Search -->
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Szukaj wpisów..."
                class="w-full pl-11 pr-4 py-3 bg-zinc-50 dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-zinc-50 rounded-xl focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent outline-none transition-all placeholder:text-zinc-500">
        </div>
        
        <!-- Filters Toggle -->
        <div class="relative">
            <button @click="openFilters = !openFilters" type="button" class="w-full md:w-auto h-full px-6 py-3 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl flex items-center justify-between gap-2 text-sm font-semibold text-zinc-900 dark:text-zinc-50 hover:border-zinc-400 dark:hover:border-zinc-600 transition-colors focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                Filtry
            </button>
            
            <!-- Filters Dropdown -->
            <div x-show="openFilters" 
                x-cloak
                @click.away="openFilters = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-1"
                class="absolute right-0 z-50 mt-2 w-full md:w-80 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-2xl p-5">
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Kategoria</label>
                        <select wire:model.live="category" class="w-full p-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm text-zinc-900 dark:text-zinc-50 focus:ring-2 focus:ring-zinc-900 outline-none">
                            <option value="">Wszystkie</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Tagi</label>
                        <select wire:model.live="tag" class="w-full p-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm text-zinc-900 dark:text-zinc-50 focus:ring-2 focus:ring-zinc-900 outline-none">
                            <option value="">Wszystkie tagi</option>
                            @foreach ($tags as $t)
                                <option value="{{ $t->slug }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Autor</label>
                        <select wire:model.live="author" class="w-full p-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm text-zinc-900 dark:text-zinc-50 focus:ring-2 focus:ring-zinc-900 outline-none">
                            <option value="">Wszyscy autorzy</option>
                            @foreach ($authors as $usr)
                                <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Sortowanie</label>
                        <select wire:model.live="sort" class="w-full p-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-sm text-zinc-900 dark:text-zinc-50 focus:ring-2 focus:ring-zinc-900 outline-none">
                            <option value="newest">Najnowsze</option>
                            <option value="oldest">Najstarsze</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <button wire:click="clearFilters" @click="openFilters = false" type="button" class="w-full py-2.5 bg-zinc-900 dark:bg-zinc-50 hover:bg-zinc-800 dark:hover:bg-zinc-200 text-white dark:text-zinc-900 rounded-lg text-sm font-semibold transition-colors">
                            Wyczyść widok
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Posts Grid -->
    <div class="grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3 relative">
        <div wire:loading class="absolute inset-0 z-10 bg-white/60 dark:bg-zinc-950/60 backdrop-blur-[2px] rounded-2xl flex justify-center mt-20">
            <span class="text-sm font-medium text-zinc-500">Odświeżanie...</span>
        </div>

        @forelse ($posts as $post)
            <article class="group flex flex-col h-full relative" wire:key="post-{{ $post->id }}">
                <a href="{{ route('posts.show', $post->slug) }}" wire:navigate class="block relative aspect-[3/2] w-full bg-zinc-100 dark:bg-zinc-900 rounded-2xl overflow-hidden mb-6">
                    @if($post->photo)
                        <img src="{{ $post->photo }}" alt="{{ $post->title }}" class="absolute inset-0 w-full h-full object-cover transition-transform ease-out group-hover:scale-110">
                    @else
                        <div class="absolute inset-0 w-full h-full bg-zinc-200 dark:bg-zinc-800 flex items-center justify-center transition-transform ease-out group-hover:scale-110">
                            <span class="text-4xl grayscale opacity-50">📰</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 ring-1 ring-inset ring-black/5 dark:ring-white/10 rounded-2xl pointer-events-none"></div>
                </a>

                <div class="flex flex-col flex-grow">
                    <div class="flex items-center gap-x-3 text-xs mb-3">
                        <time datetime="{{ $post->created_at->format('Y-m-d') }}" class="text-zinc-500 dark:text-zinc-400">
                            {{ $post->created_at->isoFormat('D MMM YYYY') }}
                        </time>
                        @if($post->category)
                            <span class="text-zinc-300 dark:text-zinc-700">&middot;</span>
                            <span class="font-medium text-zinc-900 dark:text-zinc-300">{{ $post->category->name }}</span>
                        @endif
                    </div>
                    
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-50 mb-3 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                        <a href="{{ route('posts.show', $post->slug) }}" wire:navigate class="focus:outline-none">
                            <span class="absolute inset-0 z-10" aria-hidden="true"></span>
                            {{ $post->title }}
                        </a>
                    </h3>
                    
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-3 mb-6">
                        {{ $post->lead ?? Str::limit(strip_tags($post->content), 120) }}
                    </p>
                    
                    <div class="mt-auto flex items-center gap-x-3">
                        <div class="h-8 w-8 rounded-full bg-zinc-200 dark:bg-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-600 dark:text-zinc-300">
                            {{ strtoupper(substr($post->user?->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="text-sm">
                            <p class="font-semibold text-zinc-900 dark:text-zinc-50">
                                {{ $post->user?->name ?? 'Anonim' }}
                            </p>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full py-20 text-center">
                <span class="text-4xl mb-4 block">🔍</span>
                <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-50 mb-2">Nic nie znaleziono</h3>
                <p class="text-zinc-500 dark:text-zinc-400">Spróbuj zmienić parametry wyszukiwania.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-16">
        {{ $posts->links() }}
    </div>
</div>