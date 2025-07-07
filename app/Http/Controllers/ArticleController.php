<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use App\DTOs\ArticleDTO;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Tag;
use Illuminate\View\View;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(): JsonResponse
    {
        $articles = $this->articleService->listArticles();
        $dtos = $articles->map(fn($article) => ArticleDTO::fromModel($article));
        return response()->json($dtos);
    }

    public function show($id): JsonResponse
    {
        $article = $this->articleService->getArticle($id);
        return response()->json(ArticleDTO::fromModel($article));
    }

    public function create(): View
    {
        $tags = Tag::all();
        return view('article-management.add_article', compact('tags'));
    }

    public function store(StoreArticleRequest $request): JsonResponse
    {
        $article = $this->articleService->createArticle($request->validated());
        return response()->json(ArticleDTO::fromModel($article), 201);
    }

    public function update(UpdateArticleRequest $request, $id): JsonResponse
    {
        $article = $this->articleService->updateArticle($id, $request->validated());
        return response()->json(ArticleDTO::fromModel($article));
    }

    public function destroy($id): JsonResponse
    {
        $this->articleService->deleteArticle($id);
        return response()->json(null, 204);
    }
}
