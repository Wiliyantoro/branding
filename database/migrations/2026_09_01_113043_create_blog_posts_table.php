<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('category')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->longText('safe_content_html')->nullable(); // Cached sanitized HTML
            $table->timestamps();

            // Index optimizations for MySQL/PostgreSQL production environments
            // SQLite ignores these but they help when migrating to a real database
            $table->index(['is_published', 'published_at']);
            $table->index('category');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
