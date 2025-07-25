<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use App\DTOs\ArticleDTO;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Tag;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(): JsonResponse
    {
        $articles = Article::with('tags')
            ->where('status', 'approved')
            ->orderByDesc('views')
            ->get();
        $dtos = $articles->map(fn($article) => ArticleDTO::fromModel($article));
        return response()->json($dtos);
    }

    public function show($id)
    {
        $article = $this->articleService->getArticle($id);
        $article->increment('views');
        return view('article-management.show_article', compact('article'));
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

    public function preview(\Illuminate\Http\Request $request)
    {

        $articleData = $request->all();
        $tags = Tag::find($articleData['tags'] ?? []);

        return view('article-management.preview_article', [
            'title' => $articleData['title'] ?? '',
            'summary' => $articleData['summary'] ?? '',
            'article' => $articleData['article'] ?? '',
            'tags' => $articleData['tags'] ?? '',
            'tagModels' => $tags,
        ]);
    }

    public function backtoCreate(Request $request): RedirectResponse
    {
        return redirect()->route('articles.create')
            ->withInput($request->all());
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('admin-panel.articles.edit', compact('article'));
    }
}
