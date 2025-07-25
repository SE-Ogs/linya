<?php

namespace App\Services;

use App\Repositories\ArticleRepository;

class ArticleService
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function listArticles()
    {
        return $this->articleRepository->all();
    }

    public function getArticle($id)
    {
        return $this->articleRepository->find($id);
    }

    public function createArticle(array $data)
    {
        $data['views'] = 0;

        if (!isset($data['status'])) {
            $data['status'] = 'Published'; // TODO CHANGE TO PENDING
        }
        return $this->articleRepository->create($data);
    }

    public function updateArticle($id, array $data)
    {
        return $this->articleRepository->update($id, $data);
    }

    public function deleteArticle($id)
    {
        return $this->articleRepository->delete($id);
    }
}
