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
        // Tabel tags
        Schema::create('tags', function (Blueprint $table) {
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->string('id', 36)->primary();
            $table->string('name', 100)->notNull();
            $table->string('slug', 150)->unique()->index();
            $table->timestamps();
        });

        // Tabel pivot project_tag
        Schema::create('project_tag', function (Blueprint $table) {
            $table->collation = 'utf8mb4_0900_ai_ci';
            $table->string('id', 36)->primary();
            $table->string('project_id', 36)->index();
            $table->string('tag_id', 36)->index();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade');

            $table->foreign('tag_id')
                  ->references('id')
                  ->on('tags')
                  ->onDelete('cascade');

            // Unique constraint untuk mencegah duplikasi tag yang sama pada project yang sama
            $table->unique(['project_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_tag');
        Schema::dropIfExists('tags');
    }
};