<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('a user can comment on a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['is_published' => true]);

    $response = actingAs($user)->post(route('posts.comments.store', $post), [
        'content' => 'This is a test comment',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'user_id' => $user->id,
        'parent_id' => null,
        'content' => 'This is a test comment',
    ]);
});

test('a user can reply to a comment', function () {
    $user = User::factory()->create();
    $replyUser = User::factory()->create();
    $post = Post::factory()->create(['is_published' => true]);

    $comment = Comment::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
        'content' => 'First comment',
    ]);

    $response = actingAs($replyUser)->post(route('posts.comments.store', $post), [
        'content' => 'This is a reply',
        'parent_id' => $comment->id,
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'user_id' => $replyUser->id,
        'parent_id' => $comment->id,
        'content' => 'This is a reply',
    ]);
});

test('replies to replies do not exceed depth of 1', function () {
    $user = User::factory()->create();
    $replyUserLevel1 = User::factory()->create();
    $replyUserLevel2 = User::factory()->create();
    $post = Post::factory()->create(['is_published' => true]);

    $comment = Comment::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
        'content' => 'Top level comment',
    ]);

    $reply1 = Comment::factory()->create([
        'post_id' => $post->id,
        'user_id' => $replyUserLevel1->id,
        'parent_id' => $comment->id,
        'content' => 'Reply level 1',
    ]);

    $response = actingAs($replyUserLevel2)->post(route('posts.comments.store', $post), [
        'content' => 'Reply level 2 attempt',
        'parent_id' => $reply1->id,
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'user_id' => $replyUserLevel2->id,
        'parent_id' => $comment->id, // Attached to parent of reply
        'content' => 'Reply level 2 attempt',
    ]);
});
