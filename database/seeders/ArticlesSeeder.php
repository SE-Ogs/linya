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
        // Slugs of the predefined tags (as per TagsSeeder)
        $predefinedSlugs = [
            'software-engineering',
            'game-development',
            'real-estate-management',
            'animation',
            'multimedia-arts-and-design'
        ];

        // Fetch the tags by slug
        $tags = Tag::whereIn('slug', $predefinedSlugs)->get();

        // Generate 100 articles and assign 1 random tag each
        Article::factory(100)->create()->each(function ($article) use ($tags) {
            $randomTag = $tags->random();
            $article->tags()->attach($randomTag->id);

            // Simulate fake comment count
            $article->update([
                'comments_count' => rand(0, 30)
            ]);
        });
    }
}
