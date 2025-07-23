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
        $article = $this->faker->paragraphs(3, true);
        return [
            'title' => $this->faker->sentence(),
            'article' => $article,
            'summary' => substr($article,0,100),
            'status' => $this->faker->randomElement(['pending', 'approved', 'published', 'rejected']),
            'views' => $this->faker->numberBetween(1,50),
        ];
    }
}
