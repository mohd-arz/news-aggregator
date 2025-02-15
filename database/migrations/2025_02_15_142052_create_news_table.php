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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('url');
            $table->string('image_url')->nullable();
            $table->timestamp('published_at');
            $table->enum('providers', ['newsapi']);
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('set null');
            $table->foreign('source_id')->references('id')->on('sources')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
