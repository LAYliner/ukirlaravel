<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    // ==================== KONFIGURASI TABEL ====================
    protected $table = 'categories';
    protected $primaryKey = 'id';
    
    // UUID Configuration
    public $incrementing = false;
    protected $keyType = 'string';

    // Timestamps Configuration (Sesuai skema konteks.md: hanya created_at)
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null; 
    public $timestamps = true; // Tetap true agar created_at otomatis terisi

    // ==================== FILLABLE ATTRIBUTES ====================
    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        // 'created_at' dihapus dari fillable untuk keamanan
    ];

    // ==================== CASTS ====================
    protected $casts = [
        'created_at' => 'datetime',
        // 'updated_at' dihapus karena kolom tidak ada
    ];

    // ==================== BOOT METHOD (UUID GENERATOR) ====================
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate UUID jika ID kosong
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // ==================== RELASI ====================
    /**
     * Relasi ke Blog
     * Category hasMany Blog
     */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'category_id', 'id');
    }
}