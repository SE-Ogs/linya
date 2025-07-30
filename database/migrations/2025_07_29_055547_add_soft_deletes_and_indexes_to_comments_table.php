<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only run if the comments table exists
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                // Add soft deletes if not already present
                if (!Schema::hasColumn('comments', 'deleted_at')) {
                    $table->softDeletes();
                }

                // Add parent_id column if not already present
                if (!Schema::hasColumn('comments', 'parent_id')) {
                    $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
                }

                // Check if indexes exist before creating them
                // Note: Laravel doesn't have a direct way to check for index existence
                // So we'll wrap in try-catch or just attempt to add them
            });

            // Add indexes separately to avoid conflicts
            try {
                Schema::table('comments', function (Blueprint $table) {
                    $table->index(['article_id', 'parent_id'], 'comments_article_parent_index');
                });
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }

            try {
                Schema::table('comments', function (Blueprint $table) {
                    $table->index(['user_id'], 'comments_user_index');
                });
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }

            try {
                Schema::table('comments', function (Blueprint $table) {
                    $table->index(['created_at'], 'comments_created_at_index');
                });
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                if (Schema::hasColumn('comments', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }

                // Drop indexes if they exist
                try {
                    $table->dropIndex('comments_article_parent_index');
                } catch (\Exception $e) {
                    // Index might not exist
                }

                try {
                    $table->dropIndex('comments_user_index');
                } catch (\Exception $e) {
                    // Index might not exist
                }

                try {
                    $table->dropIndex('comments_created_at_index');
                } catch (\Exception $e) {
                    // Index might not exist
                }
            });
        }
    }
};
