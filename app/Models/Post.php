<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'lead',
        'content',
        'user_id',
        'category_id',
        'photo',
        'is_published',
    ];

    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (empty($post->photo)) {
                $post->photo = Http::withoutRedirecting()
                    ->get('https://picsum.photos/1920/1080')
                    ->header('Location') ?: 'https://picsum.photos/1920/1080';
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
