<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'phone',
        'role',
        'is_active',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ==================== RELASI ====================

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'user_id');
    }

    // ==================== ROLE HELPERS ====================

    /**
     * Check if user has admin role.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has author role.
     */
    public function isAuthor(): bool
    {
        return $this->role === 'author';
    }

    /**
     * Check if user has user role (optional).
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user is active (optional helper).
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }
}