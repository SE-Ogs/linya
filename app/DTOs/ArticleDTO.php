<?php

namespace App\DTOs;

use App\Models\Article;

class ArticleDTO
{
    public $id;
    public $title;
    public $article;
    public $status;
    public $tags;
    public $created_at;
    public $updated_at;

    public function __construct($id, $title, $article, $status, $tags, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->title = $title;
        $this->article = $article;
        $this->status = $status;
        $this->tags = $tags;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public static function fromModel(Article $article)
    {
        return new self(
            $article->id,
            $article->title,
            $article->article,
            $article->status,
            $article->tags,
            $article->created_at,
            $article->updated_at
        );
    }
} 