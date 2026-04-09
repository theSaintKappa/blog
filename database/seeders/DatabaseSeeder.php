<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => bcrypt('password'),
                'role' => Role::Admin,
            ]
        );

        $categories = [
            'Technology' => 'technology',
            'Lifestyle' => 'lifestyle',
            'Programming' => 'programming',
            'Design' => 'design',
            'Travel' => 'travel',
            'Food' => 'food',
        ];

        foreach ($categories as $name => $slug) {
            Category::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        $tags = [
            'PHP' => 'php',
            'Laravel' => 'laravel',
            'Filament' => 'filament',
            'Web Development' => 'web-development',
            'Productivity' => 'productivity',
            'JavaScript' => 'javascript',
            'CSS' => 'css',
        ];

        foreach ($tags as $name => $slug) {
            Tag::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        Post::factory(25)->state(function () {
            $createdAt = fake()->dateTimeBetween('-1 year', 'now');

            return [
                'created_at' => $createdAt,
                'updated_at' => fake()->dateTimeBetween($createdAt, 'now'),
            ];
        })->create()->each(function ($post) use ($users) {
            $post->tags()->attach(
                Tag::inRandomOrder()->take(rand(1, 3))->pluck('id')
            );

            Comment::factory(rand(0, 10))->create([
                'post_id' => $post->id,
                'user_id' => fn () => $users->random()->id,
            ]);
        });
    }
}
