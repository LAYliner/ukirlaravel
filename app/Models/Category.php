<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // ==================== KONFIGURASI TABEL ====================

    protected $table = 'categories';
    protected $primaryKey = 'id';
    public $incrementing = false; // UUID
    protected $keyType = 'string';

    // ==================== FILLABLE ATTRIBUTES ====================

    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'created_at',
    ];

    // ==================== CASTS ====================

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi ke Blog
     * Category hasMany Blog
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category_id');
    }
}