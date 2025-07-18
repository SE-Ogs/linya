<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Tag;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = Tag::all();
        Article::factory(100)->create()->each(function ($article) use ($tags) {
            // Attach 1-3 random tags to each article
            $randomTags = $tags->random(rand(1, min(3, $tags->count())));
            $article->tags()->attach($randomTags->pluck('id')->toArray());
        });
    }
}
