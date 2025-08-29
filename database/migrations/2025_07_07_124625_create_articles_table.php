<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Articles table
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('author');
            $table->string('summary');
            $table->longText('article');
            $table->enum('status', ['Pending', 'Approved', 'Published', 'Rejected'])->default('Published');
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->string('rejection_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_tag');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('tags');
    }
};
