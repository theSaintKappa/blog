<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        return view('posts.index');
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->when(auth()->user()?->role !== Role::Admin, function ($query) {
                $query->where('is_published', true);
            })
            ->with(['tags', 'comments' => function ($query) {
                $query->whereNull('parent_id')->with(['user', 'replies.user'])->latest();
            }])
            ->firstOrFail();

        $relatedPosts = Post::where('id', '!=', $post->id)
            ->when(auth()->user()?->role !== Role::Admin, function ($query) {
                $query->where('is_published', true);
            })
            ->whereHas('tags', function ($query) use ($post) {
                $query->whereIn('tags.id', $post->tags->pluck('id'));
            })
            ->withCount(['tags' => function ($query) use ($post) {
                $query->whereIn('tags.id', $post->tags->pluck('id'));
            }])
            ->orderByDesc('tags_count')
            ->latest()
            ->limit(3)
            ->get();

        return view('posts.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $parameters = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:posts,slug'],
            'lead' => ['nullable', 'string'],
            'author' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $post = new Post;

        $post->title = $parameters['title'];
        $post->slug = $parameters['slug'];
        $post->lead = $parameters['lead'] ?? null;
        $post->author = $parameters['author'];
        $post->content = $parameters['content'];

        // Post::create($parameters);

        $post->save();

        return redirect()->route('posts.index');
    }

    public function publish(Request $request, Post $post)
    {
        if ($request->user()->cannot('update', $post)) {
            abort(403);
        }

        $post->update(['is_published' => true]);

        return back();
    }

    public function destroy(Request $request, Post $post)
    {
        if ($request->user()->cannot('delete', $post)) {
            abort(403);
        }

        $post->delete();

        return redirect()->route('posts.index');
    }
}
