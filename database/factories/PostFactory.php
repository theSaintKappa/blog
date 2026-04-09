<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'lead' => $this->faker->paragraph(),
            'content' => collect($this->faker->paragraphs(rand(8, 20)))->map(function ($p, $index) {
                if ($index > 0 && $index % 4 === 0) {
                    return '<h2>'.Str::title($this->faker->words(rand(3, 6), true))."</h2><p>{$p}</p>";
                }

                return "<p>{$p}</p>";
            })->implode(''),
            'photo' => 'https://picsum.photos/seed/'.Str::uuid().'/1920/1080',
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'is_published' => $this->faker->boolean(80),
        ];
    }
}
