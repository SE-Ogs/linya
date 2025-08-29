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
use App\Models\ArticleImage;
use Illuminate\Support\Facades\Storage;

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
            'replies',
            'likes' // Load likes for user reactions
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
            return view('partials.comments-list', compact('comments', 'article', 'sort'));
        }

        return view('article-management.show-article', compact('article', 'comments', 'sort'));
    }


    public function create(): View
    {
        $tags = Tag::all();

        // Get form data from session if available
        $formData = session('article_form_data', []);

        \Log::info('Form data from session (create):', $formData);

        return view('article-management.add-article', compact('tags', 'formData'));
    }


    public function store(StoreArticleRequest $request)
    {
        $validatedData = $request->validated();

        // Create the article
        $article = $this->articleService->createArticle($validatedData);

        // Handle image processing
        $this->processArticleImages($article, $request);

        // ✅ Clear the form data only after successful save
        $request->session()->forget('article_form_data');

        if ($request->expectsJson()) {
            return response()->json(ArticleDTO::fromModel($article), 201);
        }

        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.articles')->with('success', 'Article created successfully!');
        } else {
            return redirect()->route('writer.articles')->with('success', 'Article created successfully!');
        }
    }


    public function destroy($id)
    {
        $this->articleService->deleteArticle($id);

        return redirect()->route('admin.articles')->with('success', 'Article deleted!');
    }

    public function previewExisting(Article $article)
    {
        // Convert existing article images to the format expected by the preview
        $images = [];
        if ($article->images && $article->images->count() > 0) {
            foreach ($article->images->sortBy('order') as $image) {
                $images[] = [
                    'name' => basename($image->image_path),
                    'dataUrl' => asset('storage/' . $image->image_path),
                    'alt_text' => $image->alt_text ?? $article->title,
                    'existing' => true, // Flag to indicate this is an existing image
                    'id' => $image->id
                ];
            }
        }

        // Get tags for the article
        $tags = $article->tags->pluck('id')->toArray();
        $tagModels = $article->tags;


        return view('article-management.preview-existing-article', compact('article'));
    }

    public function preview(Request $request)
    {
        $articleData = $request->all();
        $tags = Tag::find($articleData['tags'] ?? []);

        // Handle image data
        $images = [];
        if ($request->has('imageData')) {
            $images = json_decode($request->input('imageData'), true) ?? [];
        }

        return view('article-management.preview-article', [
            'title' => $articleData['title'] ?? '',
            'author' => $articleData['author'] ?? '',
            'summary' => $articleData['summary'] ?? '',
            'article' => $articleData['article'] ?? '',
            'tags' => $articleData['tags'] ?? '',
            'tagModels' => $tags,
            'images' => $images,
            'formData' => $articleData, // Store all form data for back to editor
            'fromManagement' => false
        ]);
    }

    public function backToEditor(Request $request)
    {
        // Get all the data from the request
        $data = $request->all();

        // Handle image data properly
        $images = [];
        if ($request->has('imageData') && !empty($request->input('imageData'))) {
            $imageData = json_decode($request->input('imageData'), true);
            if (is_array($imageData)) {
                $images = $imageData;
            }
        }

        // Get tags
        $selectedTags = $request->input('tags', []);
        $tags = Tag::all();

        // Determine route prefix based on user role
        $routePrefix = auth()->user()->isAdmin ? 'admin' : 'writer';

        return view('article-management.add-article', [
            'title' => $data['title'] ?? '',
            'summary' => $data['summary'] ?? '',
            'author' => $data['author'] ?? '',
            'article' => $data['article'] ?? '',
            'tags' => $tags,
            'selectedTags' => $selectedTags,
            'images' => $images, // Pass images back to the editor
            'fromPreview' => true // Flag to indicate we're coming from preview
        ]);
    }

    public function edit($id)
    {
        // Find the article by ID
        $article = Article::with('tags')->findOrFail($id);

        // Get all available tags
        $tags = Tag::all();

        // Pass the article and tags to the view
        return view('article-management.edit-article', compact('article', 'tags'));
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
            'author' => 'required|string|max:255',
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
        return redirect()->route('admin.articles.edit', $id)->with('success', 'Article updated successfully!');
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

    public function deleteArticleImage($imageId): JsonResponse
    {
        $image = ArticleImage::findOrFail($imageId);
        $this->deleteImage($image);
        return response()->json(null, 204);
    }

    protected function processArticleImages(Article $article, Request $request): void
    {
        if ($request->filled('imageData')) {
            $imageData = json_decode($request->imageData, true);

            \Log::info('Decoded images:', ['count' => count($imageData)]);

            foreach ($imageData as $index => $imageInfo) {
                // Handle both possible structures
                $dataUrl = $imageInfo['dataUrl'] ?? ($imageInfo['data_url'] ?? null);
                $name = $imageInfo['name'] ?? "image_$index.jpg";

                if ($dataUrl) {
                    $path = $this->storeBase64Image($dataUrl, $name);
                    $article->images()->create([
                        'image_path' => $path,
                        'order' => $index,
                        'is_featured' => $index === 0
                    ]);
                }
            }
        }
    }

    protected function storeBase64Image(string $dataUrl, string $filename): string
    {
        // Extract the base64 data from the data URL
        $base64Data = substr($dataUrl, strpos($dataUrl, ',') + 1);
        $imageData = base64_decode($base64Data);

        // Generate a unique filename
        $extension = pathinfo($filename, PATHINFO_EXTENSION) ?: 'jpg';
        $uniqueFilename = uniqid() . '.' . $extension;
        $storagePath = 'article_images/' . $uniqueFilename;

        // Store without ACL parameter
        Storage::put($storagePath, $imageData);

        return $storagePath;
    }

    protected function storeAdditionalImages(Article $article, array $images): void
    {
        foreach ($images as $index => $image) {
            $path = $this->storeBase64Image($image['dataUrl'], $image['name'] ?? "image_{$index}.jpg");

            $article->images()->create([
                'image_path' => $path, // Store path only, not URL
                'order' => $index,
                'is_featured' => $index === 0,
            ]);
        }
    }

    protected function deleteImage(ArticleImage $image): void
    {
        Storage::delete($image->image_path);
        $image->delete();
    }
}
