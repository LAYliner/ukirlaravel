<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel-tabel bawaan Laravel yang tidak digunakan di project ini.
     *
     * Alasan penghapusan:
     *  - cache, cache_locks   → CACHE_DRIVER=file (tidak pakai database)
     *  - sessions             → SESSION_DRIVER=file (tidak pakai database)
     *  - jobs, job_batches,
     *    failed_jobs          → QUEUE_CONNECTION=sync (tidak pakai queue)
     *
     * Tabel yang TIDAK dihapus:
     *  - password_reset_tokens → tetap digunakan untuk fitur reset password
     */

    public function up(): void
    {
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
    }

    /**
     * Tabel ini memang tidak dipakai, jadi rollback tidak membuatnya kembali.
     */
    public function down(): void
    {
        //
    }
};
