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
        Schema::create('projects', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('user_id', 36)->index('idx_projects_user_id');
            $table->string('title');
            $table->string('slug')->unique('idx_projects_slug');
            $table->longText('description');
            $table->string('thumbnail_path')->nullable();
            $table->string('client_name')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft')->index('idx_projects_status');
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('views')->default(0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable()->index('idx_projects_deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
