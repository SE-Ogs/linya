<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Software Engineering',
            'Game Development',
            'Real Estate Management',
            'Animation',
            'Multimedia Arts',
        ];

        foreach ($tags as $tag) {
            DB::table('tags')->updateOrInsert(
                ['slug' => Str::slug($tag)],
                [
                    'name' => $tag,
                    'slug' => Str::slug($tag),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
} 