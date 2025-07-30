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
use App\Models\Comment;

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

    public function show($id, Request $request)
{
    $article = $this->articleService->getArticle($id);
    $article->increment('views');

    $sort = $request->query('sort', 'all');

    $commentsQuery = Comment::with([
        'user',
        'children',
        'userReaction' // 🔥 include the current user's reaction
    ])
        ->where('article_id', $article->id)
        ->whereNull('parent_id');

    switch ($sort) {
        case 'newest':
            $commentsQuery->orderByDesc('created_at');
            break;
        case 'oldest':
            $commentsQuery->orderBy('created_at');
            break;
        case 'most_liked':
            $commentsQuery->withCount('likes')->orderByDesc('likes_count');
            break;
        case 'all':
        default:
            $commentsQuery->orderByDesc('created_at');
            break;
    }

    $comments = $commentsQuery->get();

    if ($request->ajax()) {
        return view('partials.comments_list', compact('comments', 'article', 'sort'));
    }

    return view('article-management.show_article', compact('article', 'comments', 'sort'));
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

    public function destroy($id)
    {
        $this->articleService->deleteArticle($id);

        return redirect()->route('admin.articles')->with('success', 'Article deleted!');
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
        // Find the article by ID
        $article = Article::with('tags')->findOrFail($id);

        // Get all available tags
        $tags = Tag::all();

        // Pass the article and tags to the view
        return view('article-management.edit_article', compact('article', 'tags'));
    }

    public function update(Request $request, $id)
    {
        // Check if the request is coming from an API or a web form
        if ($request->expectsJson()) {
            // Handle API request
            $article = $this->articleService->updateArticle($id, $request->validated());
            return response()->json(ArticleDTO::fromModel($article));
        }

        // Handle web form request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string|max:255',
            'article' => 'required|string',
            'tags' => 'array', // Optional: Ensure tags are an array
            'tags.*' => 'exists:tags,id', // Ensure each tag exists in the tags table
        ]);

        // Find the article by ID
        $article = Article::findOrFail($id);

        // Update the article fields
        $article->title = $validatedData['title'];
        $article->summary = $validatedData['summary'];
        $article->article = $validatedData['article'];
        $article->save();

        // Sync the tags (if provided)
        if (isset($validatedData['tags'])) {
            $article->tags()->sync($validatedData['tags']);
        }

        // Redirect back with a success message
        return redirect()->route('articles.edit', $id)->with('success', 'Article updated successfully!');
    }

    public function approve($id)
    {
        $this->articleService->approveArticle($id);
        return redirect()->route('admin.articles')->with('success', 'Article approved!');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);
        $this->articleService->rejectArticle($id, $request->input('rejection_reason'));
        return redirect()->route('admin.articles')->with('success', 'Article has been rejected.');
    }

    public function publish($id)
    {
        $this->articleService->publishArticle($id);
        return redirect()->route('admin.articles')->with('success', 'Article published!');
    }
}
