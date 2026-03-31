<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    // ==================== KONFIGURASI TABEL ====================

    protected $table = 'media';
    protected $primaryKey = 'id';
    public $incrementing = false; // UUID
    protected $keyType = 'string';

    // ==================== FILLABLE ATTRIBUTES ====================

    protected $fillable = [
        'id',
        'mediable_id',
        'mediable_type',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'sort_order',
        'uploaded_by',
        'created_at',
        'updated_at',
    ];

    // ==================== CASTS ====================

    protected $casts = [
        'file_size' => 'integer',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi Polymorphic ke Pemilik Aset (Blog/Project)
     * Media belongsTo Mediable
     */
    public function mediable()
    {
        return $this->morphTo();
    }

    /**
     * Relasi ke User yang mengupload
     * Media belongsTo User (uploader)
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // ==================== HELPERS ====================

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Get full URL for the file
     */
    public function getUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    /**
     * Check if file is image
     */
    public function isImage(): bool
    {
        return str_starts_with($this->file_type, 'image/');
    }

    /**
     * Check if file is document
     */
    public function isDocument(): bool
    {
        return str_starts_with($this->file_type, 'application/');
    }
}