<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('media')) {
            if (DB::getDriverName() !== 'sqlite') {
                Schema::table('media', function (Blueprint $table) {
                    $table->dropForeign('fk_media_uploaded_by');
                });
            }

            Schema::dropIfExists('media');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('mediable_id', 36)->comment('ID entitas pemilik (users.id, blogs.id, projects.id)');
            $table->string('mediable_type')->comment('Class namespace (App\\Models\\User, App\\Models\\Blog)');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type', 50)->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('uploaded_by', 36)->nullable()->index('fk_media_uploaded_by');
            $table->dateTime('created_at')->nullable()->useCurrent();

            $table->index(['mediable_id', 'mediable_type'], 'mediable_id');
            $table->foreign(['uploaded_by'], 'fk_media_uploaded_by')
                ->references(['id'])
                ->on('users')
                ->onUpdate('no action')
                ->onDelete('set null');
        });
    }
};
