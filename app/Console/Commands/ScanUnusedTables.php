<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class ScanUnusedTables extends Command
{
    protected $signature = 'scan:unused-tables
                            {--path=* : Tambahan direktori untuk discan}
                            {--ignore=* : Tabel yang ingin diabaikan (misal: migrations)}';

    protected $description = 'Mencari tabel database yang mungkin tidak digunakan di project';

    /**
     * Tabel sistem yang selalu diabaikan secara default.
     */
    protected array $systemTables = [
        'migrations',
        'failed_jobs',
        'password_resets',
        'password_reset_tokens',
        'personal_access_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
    ];

    public function handle(): int
    {
        // ─── 1. Ambil semua tabel dari database ────────────────────────────
        $allTables = $this->getDatabaseTables();

        if ($allTables->isEmpty()) {
            $this->error("Tidak ada tabel ditemukan. Periksa koneksi database.");
            return 1;
        }

        $this->info("Ditemukan {$allTables->count()} tabel di database.");

        // ─── 2. Tentukan direktori yang akan discan ─────────────────────────
        $scanPaths = array_filter([
            app_path(),
            database_path('seeders'),
            base_path('routes'),
            ...array_map('base_path', $this->option('path')),
        ], 'is_dir');

        // ─── 3. Scan kode ───────────────────────────────────────────────────
        $this->info("Memindai direktori: " . implode(', ', $scanPaths));
        $usedTables = $this->scanForUsedTables($scanPaths);

        $this->info("Terdeteksi {$usedTables->count()} referensi tabel unik dalam kode.");

        // ─── 4. Tabel yang diabaikan ────────────────────────────────────────
        $ignoredTables = collect(array_merge(
            $this->systemTables,
            $this->option('ignore')
        ));

        // ─── 5. Bandingkan ──────────────────────────────────────────────────
        $unused = $allTables
            ->diff($usedTables)
            ->diff($ignoredTables)
            ->values();

        $this->newLine();

        if ($unused->isEmpty()) {
            $this->info("✅ Semua tabel terdeteksi digunakan dalam kode.");
        } else {
            $this->warn("⚠️  Tabel yang TIDAK terdeteksi penggunaannya ({$unused->count()} tabel):");
            $this->table(
                ['#', 'Table Name'],
                $unused->map(fn($t, $i) => [$i + 1, $t])->toArray()
            );
            $this->newLine();
            $this->comment("💡 Catatan: Deteksi ini tidak 100% akurat.");
            $this->comment("   Tabel mungkin digunakan secara dinamis, via raw SQL kompleks,");
            $this->comment("   atau dari package pihak ketiga. Verifikasi manual tetap diperlukan.");
        }

        // ─── 6. Info debug: tabel apa saja yang terdeteksi ─────────────────
        if ($this->option('verbose')) {
            $this->newLine();
            $this->info("Tabel yang terdeteksi digunakan:");
            $this->table(['Table Name', 'Source'], $usedTables->map(fn($t) => [$t, '✓'])->toArray());
        }

        return 0;
    }

    /**
     * Ambil semua tabel dari database secara driver-agnostic.
     */
    protected function getDatabaseTables(): \Illuminate\Support\Collection
    {
        $driver = DB::getDriverName();

        $tables = match ($driver) {
            'mysql', 'mariadb' => DB::select('SHOW TABLES'),
            'pgsql'  => DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'"),
            'sqlite' => DB::select("SELECT name FROM sqlite_master WHERE type='table'"),
            default  => throw new \RuntimeException("Driver '$driver' belum didukung."),
        };

        return collect($tables)->map(fn($row) => array_values((array) $row)[0]);
    }

    /**
     * Scan semua file PHP di direktori yang diberikan.
     */
    protected function scanForUsedTables(array $paths): \Illuminate\Support\Collection
    {
        $usedTables = collect();
        $finder = new Finder();
        $finder->files()->in($paths)->name('*.php');

        $bar = $this->output->createProgressBar(iterator_count($finder));
        $bar->start();

        foreach ($finder as $file) {
            $content = file_get_contents($file->getRealPath());
            $usedTables = $usedTables->merge($this->extractTableNames($content));
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        return $usedTables->unique()->values();
    }

    /**
     * Ekstrak semua nama tabel dari konten file PHP.
     */
    protected function extractTableNames(string $content): array
    {
        $tables = [];

        // ── A. Eloquent: protected $table = 'nama' ──────────────────────────
        if (preg_match('/protected\s+\$table\s*=\s*[\'"]([a-zA-Z_][a-zA-Z0-9_]*)[\'"]/', $content, $m)) {
            $tables[] = $m[1];
        }

        // ── B. Eloquent konvensi: class NamaModel extends Model ─────────────
        // Hanya sebagai fallback jika $table tidak didefinisikan
        if (!str_contains($content, '$table') &&
            (str_contains($content, 'extends Model') || str_contains($content, 'extends Authenticatable'))) {
            if (preg_match('/class\s+([A-Z][a-zA-Z0-9]+)\s+extends/', $content, $m)) {
                $tables[] = Str::snake(Str::plural($m[1]));
            }
        }

        // ── C. Query Builder: DB::table('nama') ─────────────────────────────
        preg_match_all(
            '/DB\s*::\s*table\s*\(\s*[\'"]([a-zA-Z_][a-zA-Z0-9_]*)[\'"]\s*\)/',
            $content,
            $matches
        );
        array_push($tables, ...$matches[1]);

        // ── D. Schema Builder: Schema::create/drop/table('nama') ────────────
        preg_match_all(
            '/Schema\s*::\s*(?:create|drop|table|dropIfExists|rename|hasTable|hasColumn)\s*\(\s*[\'"]([a-zA-Z_][a-zA-Z0-9_]*)[\'"]\s*[,)]/',
            $content,
            $matches
        );
        array_push($tables, ...$matches[1]);

        // ── E. Schema rename: Schema::rename('old', 'new') ──────────────────
        preg_match_all(
            '/Schema\s*::\s*rename\s*\(\s*[\'"]([a-zA-Z_][a-zA-Z0-9_]*)[\'"]\s*,\s*[\'"]([a-zA-Z_][a-zA-Z0-9_]*)[\'"]\s*\)/',
            $content,
            $matches
        );
        array_push($tables, ...$matches[1], ...$matches[2]);

        return array_filter($tables); // hapus nilai kosong
    }
}