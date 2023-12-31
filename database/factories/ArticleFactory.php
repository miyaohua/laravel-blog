<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            "title"=>fake()->text(20),
            "content"=>fake()->sentence(),
            "category_id"=>Category::inRandomOrder()->first()->id,
            "user_id"=>User::inRandomOrder()->first()->id,
            "abstract"=>fake()->text(20),
            "thumbnail"=>fake()->imageUrl(300,300)
        ];
    }
}
