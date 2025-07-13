<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'article' => $this->faker->paragraphs(3, true),
            'summary' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'published', 'rejected']),
        ];
    }
}
