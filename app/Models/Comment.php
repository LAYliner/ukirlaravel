<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    // ==================== KONFIGURASI TABEL ====================

    protected $table = 'comments';
    protected $primaryKey = 'id';
    public $incrementing = false; // UUID
    protected $keyType = 'string';

    // ==================== FILLABLE ATTRIBUTES ====================

    protected $fillable = [
        'id',
        'user_id',
        'commentable_id',
        'commentable_type',
        'parent_id',
        'content',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // ==================== CASTS ====================

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELASI ====================

    /**
     * Relasi ke User (Penulis Komentar)
     * Comment belongsTo User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi Polymorphic ke Entitas yang Dikomentari (Blog/Project)
     * Comment belongsTo Commentable
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Relasi ke Parent Comment (untuk reply)
     * Comment belongsTo Comment (self-referencing)
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Relasi ke Child Comments (replies)
     * Comment hasMany Comment
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}