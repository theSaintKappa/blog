<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $validated = $request->validated();

        $parentId = $validated['parent_id'] ?? null;

        if ($parentId) {
            $parentComment = Comment::findOrFail($parentId);
            // Ensure we don't nest more than 1 level deep.
            if ($parentComment->parent_id !== null) {
                $parentId = $parentComment->parent_id;
            }
        }

        $post->comments()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'parent_id' => $parentId,
        ]);

        return back()->with('status', 'Comment added successfully!');
    }
}
