<?php

namespace Database\Seeders;

use App\Enums\Role;
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
        // User::factory(10)->create();

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
        ];

        foreach ($categories as $name => $slug) {
            \App\Models\Category::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        $tags = [
            'PHP' => 'php',
            'Laravel' => 'laravel',
            'Filament' => 'filament',
            'Web Development' => 'web-development',
            'Productivity' => 'productivity',
        ];

        foreach ($tags as $name => $slug) {
            \App\Models\Tag::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        \App\Models\Post::factory(25)->create()->each(function ($post) {
            $post->tags()->attach(
                \App\Models\Tag::inRandomOrder()->take(rand(1, 3))->pluck('id')
            );
        });
    }
}
