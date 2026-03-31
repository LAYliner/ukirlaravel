<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    // ==================== KONFIGURASI TABEL ====================

    protected $table = 'projects';
    protected $primaryKey = 'id';
    public $incrementing = false; // UUID
    protected $keyType = 'string';

    // ==================== FILLABLE ATTRIBUTES ====================

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'slug',
        'description',
        'client_name',
        'project_date',
        'status',
        'views',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // ==================== CASTS ====================

    protected $casts = [
        'project_date' => 'date',
        'views' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi ke User (Pemilik)
     * Project belongsTo User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Comments (Polymorphic)
     * Project hasMany Comment
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Relasi ke Media (Polymorphic)
     * Project hasMany Media
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    // ==================== HELPERS ====================

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->deleted_at === null;
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
}