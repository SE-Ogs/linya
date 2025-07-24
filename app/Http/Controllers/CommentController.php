<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CommentController extends Controller
{
    private $commentsFile;

    public function __construct()
    {
        // Use Laravel's storage path for better security and consistency
        $this->commentsFile = storage_path('app/comments.txt');
    }

    // Method to handle posting a new comment or reply
    public function store(Request $request)
    {
        $request->validate([
            'comment_text' => 'required|string|max:2000',
            'parent_id' => 'nullable|string',
        ]);

        $this->addComment(
            Auth::user()->name ?? 'Guest',
            $request->input('comment_text'),
            $request->input('parent_id')
        );

        $redirectUrl = $request->header('Referer', '/');
        $hash = $request->input('parent_id') ? '#comment-' . $request->input('parent_id') : '';

        return redirect(strtok($redirectUrl, '?') . $hash);
    }

    // --- Private Helper Functions ---

    private function loadComments(): array
    {
        if (!File::exists($this->commentsFile)) {
            return [];
        }

        $comments = json_decode(File::get($this->commentsFile), true) ?: [];

        return array_map(function($comment) {
            return array_merge([
                'parent_id' => null,
                'likes' => 0,
                'dislikes' => 0,
            ], $comment);
        }, $comments);
    }

    private function saveComments(array $comments): void
    {
        File::put($this->commentsFile, json_encode($comments, JSON_PRETTY_PRINT));
    }

    private function addComment(string $username, string $commentText, ?string $parentId = null): void
    {
        $comments = $this->loadComments();
        $newComment = [
            'id' => uniqid(),
            'username' => htmlspecialchars($username),
            'text' => htmlspecialchars($commentText),
            'timestamp' => time(),
            'likes' => 0,
            'dislikes' => 0,
            'parent_id' => $parentId
        ];
        $comments[] = $newComment;
        $this->saveComments($comments);
    }
}
