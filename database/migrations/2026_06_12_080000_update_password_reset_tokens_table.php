<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('created_at')->index();
            $table->boolean('verified')->default(false)->after('expires_at');
            $table->unsignedTinyInteger('attempts')->default(0)->after('verified');
            $table->index(['email', 'token']);
        });
    }

    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropIndex(['email', 'token']);
            $table->dropIndex(['expires_at']);
            $table->dropColumn(['expires_at', 'verified', 'attempts']);
        });
    }
};
