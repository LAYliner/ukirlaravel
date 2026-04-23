<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'projects';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'client_name',
        'project_date',
        'status',
        'is_visible',
        'views',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'project_date' => 'date',
        'views' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model): void {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function scopeFilterByStatus(Builder $query, ?string $status): Builder
    {
        $allowed = ['draft', 'published'];
        return $query->when(in_array($status, $allowed, true), fn(Builder $q) => $q->where('status', $status));
    }

    public function scopeFilterByDateRange(Builder $query, ?string $dateFrom, ?string $dateTo): Builder
    {
        return $query->when($dateFrom, fn(Builder $q) => $q->where('created_at', '>=', $dateFrom . ' 00:00:00'))
                     ->when($dateTo, fn(Builder $q) => $q->where('created_at', '<=', $dateTo . ' 23:59:59'));
    }

    public function scopeFilterByAuthor(Builder $query, ?string $authorId): Builder
    {
        return $query->when($authorId, fn(Builder $q) => $q->where('user_id', $authorId));
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function (Builder $q, string $term) {
            $q->where(function (Builder $sub) use ($term) {
                $sub->where('title', 'like', "%{$term}%")
                    ->orWhere('slug', 'like', "%{$term}%")
                    ->orWhere('client_name', 'like', "%{$term}%");
            });
        });
    }

    public function scopeForAuthenticatedUser(Builder $query, User $user): Builder
    {
        return $user->role !== 'admin' ? $query->where('user_id', $user->id) : $query;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'published')
                     ->where('is_visible', true);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->is_visible === true;
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
}