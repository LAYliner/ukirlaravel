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
        Schema::create('comments', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('user_id', 36)->nullable()->index('idx_comments_user_id');
            $table->string('commentable_id', 36);
            $table->string('commentable_type');
            $table->string('parent_id', 36)->nullable()->index('idx_comments_parent_id');
            $table->text('content');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable()->index('idx_comments_deleted_at');

            $table->index(['commentable_type', 'commentable_id'], 'idx_comments_commentable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
