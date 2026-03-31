<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    // ==================== KONFIGURASI TABEL ====================

    protected $table = 'blogs'; // Rename dari 'blog'
    protected $primaryKey = 'id'; // Rename dari 'id_blog'
    public $incrementing = false; // UUID
    protected $keyType = 'string';

    // ==================== FILLABLE ATTRIBUTES ====================

    protected $fillable = [
        'id',
        'user_id',           // Rename dari 'id_admin'
        'category_id',       // New
        'title',             // Rename dari 'judul'
        'slug',
        'content',           // Rename dari 'isi'
        'thumbnail_path',    // Rename dari 'thumbnail'
        'status',            // Rename dari 'status'
        'published_at',      // New
        'views',             // New
    ];

    // ==================== CASTS ====================

    protected $casts = [
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'views' => 'integer',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi ke User (Penulis)
     * Blog belongsTo User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Category
     * Blog belongsTo Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relasi ke Comments (Polymorphic)
     * Blog hasMany Comment
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Relasi ke Media (Polymorphic)
     * Blog hasMany Media
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    // ==================== HELPERS (OPTIONAL) ====================

    /**
     * Check if blog is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->deleted_at === null;
    }

    /**
     * Check if blog is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if blog is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get thumbnail URL with fallback
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : null;
    }
}