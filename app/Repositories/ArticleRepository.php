<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository
{
    public function all()
    {
        return Article::with('tags')->get();
    }

    public function find($id)
    {
        return Article::with('tags')->findOrFail($id);
    }

    public function create(array $data)
    {
        $article = Article::create($data);
        if (isset($data['tags'])) {
            $article->tags()->sync($data['tags']);
        }
        return $article;
    }

    public function update($id, array $data)
    {
        $article = Article::findOrFail($id);
        $article->update($data);
        if (isset($data['tags'])) {
            $article->tags()->sync($data['tags']);
        }
        return $article;
    }

    public function delete($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
    }
} 