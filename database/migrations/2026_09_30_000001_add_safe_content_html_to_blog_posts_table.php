<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Idempotent: the column may already exist on servers where it was added
        // manually before this migration landed.
        if (! Schema::hasColumn('blog_posts', 'safe_content_html')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->text('safe_content_html')->nullable()->after('content');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('blog_posts', 'safe_content_html')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->dropColumn('safe_content_html');
            });
        }
    }
};
