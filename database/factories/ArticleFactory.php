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
        $status = $this->faker->randomElement(['Pending', 'Approved', 'Published', 'Rejected']);

        return [
            'title' => $this->faker->sentence(),
            'author'=> $this->faker->name,
            'article' => $article,
            'summary' => substr($article, 0, 100),
            'status' => $status,
            'views' => $this->faker->numberBetween(1, 50),
            'rejection_reason' => $status === 'Rejected' ? $this->faker->sentence() : null
        ];
    }
}
