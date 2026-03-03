<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'user';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     */
    protected $keyType = 'string';

    /**
     * The name of the "created at" column.
     */
    const CREATED_AT = 'dibuat';

    /**
     * The name of the "updated at" column.
     */
    const UPDATED_AT = 'diperbarui';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'nama',
        'email',
        'nomor',
        'password',
        'role',
        'aktif',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'aktif' => 'boolean',
        'dibuat' => 'datetime',
        'diperbarui' => 'datetime',
    ];

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
}