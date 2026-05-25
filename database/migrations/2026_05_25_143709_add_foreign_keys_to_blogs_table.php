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
        Schema::table('blogs', function (Blueprint $table) {
            $table->foreign(['category_id'], 'fk_blogs_category_id')->references(['id'])->on('categories')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['user_id'], 'fk_blogs_user_id')->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropForeign('fk_blogs_category_id');
            $table->dropForeign('fk_blogs_user_id');
        });
    }
};
