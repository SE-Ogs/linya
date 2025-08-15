<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentReportController extends Controller
{
    /**
     * Store a new comment report.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'comment_id' => 'required|exists:comments,id',
                'reason' => 'required|string|in:' . implode(',', array_keys(CommentReport::getReasons())),
                'additional_info' => 'nullable|string|max:500',
            ]);

            $commentId = $request->comment_id;
            $userId = Auth::id();

            // Check if the user has already reported this comment
            $existingReport = CommentReport::where('comment_id', $commentId)
                ->where('user_id', $userId)
                ->first();

            if ($existingReport) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reported this comment.',
                ], 422);
            }

            // Check if the user is trying to report their own comment
            $comment = Comment::findOrFail($commentId);
            if ($comment->user_id === $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot report your own comment.',
                ], 422);
            }

            // Create the report
            $report = CommentReport::create([
                'comment_id' => $commentId,
                'user_id' => $userId,
                'reason' => $request->reason,
                'additional_info' => $request->additional_info,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comment reported successfully. Thank you for helping us maintain a safe community.',
                'report_id' => $report->id,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please check your input and try again.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Comment report error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'comment_id' => $request->comment_id ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your report. Please try again later.',
            ], 500);
        }
    }

    /**
     * Get report reasons for the frontend.
     */
    public function getReasons(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'reasons' => CommentReport::getReasons(),
        ]);
    }

    /**
     * Admin method: Get all reports (you can implement this for admin dashboard later).
     */
    public function index(Request $request): JsonResponse
    {
        // This method can be used for admin dashboard
        // You might want to add additional middleware to restrict access to admins only

        $query = CommentReport::with(['comment.user', 'user', 'reviewer'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'reports' => $reports,
        ]);
    }

    /**
     * Admin method: Update report status.
     */
    public function update(Request $request, CommentReport $report): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:reviewed,resolved,dismissed',
        ]);

        $report->markAsReviewed(Auth::id(), $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Report status updated successfully.',
            'report' => $report->fresh(['comment.user', 'user', 'reviewer']),
        ]);
    }
}
