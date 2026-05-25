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
        Schema::create('blogs', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('user_id', 36)->index('idx_blogs_user_id');
            $table->string('category_id', 36)->nullable()->index('idx_blogs_category_id');
            $table->string('title');
            $table->string('slug')->unique('idx_blogs_slug');
            $table->longText('content');
            $table->string('thumbnail_path')->nullable();
            $table->enum('status', ['draft', 'published'])->nullable()->default('draft')->index('idx_blogs_status');
            $table->dateTime('published_at')->nullable();
            $table->unsignedInteger('views')->nullable()->default(0);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
