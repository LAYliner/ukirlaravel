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
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign(['parent_id'], 'fk_comments_parent_id')->references(['id'])->on('comments')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['user_id'], 'fk_comments_user_id')->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('fk_comments_parent_id');
            $table->dropForeign('fk_comments_user_id');
        });
    }
};
