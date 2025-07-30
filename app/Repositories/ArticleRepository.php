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

    public function approve($id)
    {
        $article = Article::findOrFail($id);
        $article->update(['status' => 'Approved', 'rejection_reason' => null]);
        return $article;
    }

    public function publish($id)
    {
        $article = Article::findOrFail($id);
        $article->update(['status' => 'Published']);
        return $article;
    }

    public function reject($id, $reason)
    {
        $article = Article::findOrFail($id);
        $article->update([
            'status' => 'Rejected',
            'rejection_reason' => $reason
        ]);
        return $article;
    }
} 