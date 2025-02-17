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
        Schema::table('news', function (Blueprint $table) {
            $table->fullText(['title', 'description', 'body']);
            $table->index('published_at');
            $table->index('category_id');
            $table->index('source_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropFullText(['title', 'description', 'body']);
            $table->dropIndex(['published_at']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['source_id']);
        });
    }
};
