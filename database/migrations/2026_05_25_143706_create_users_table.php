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
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('name');
            $table->string('email')->unique('email');
            $table->string('password');
            $table->enum('role', ['admin', 'author', 'user'])->nullable()->default('user');
            $table->string('phone', 20)->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->rememberToken();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->useCurrentOnUpdate()->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
