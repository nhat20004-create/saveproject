<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    public const DRAFT = 'draft';
    public const PUBLISHED = 'published';
    public const SCHEDULED = 'scheduled';

    public const STATUSES = [
        self::DRAFT => 'Nháp',
        self::PUBLISHED => 'Đã đăng',
        self::SCHEDULED => 'Hẹn giờ đăng',
    ];

    protected $fillable = [
        'user_id', 'category_id', 'slug', 'title', 'content',
        'file_path', 'status', 'published_at',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::PUBLISHED);
    }

    public function scopeFilter(Builder $query, array $f): Builder
    {
        return $query
            ->when($f['q'] ?? null, function ($q, $term) {
                $q->where(fn ($w) => $w
                    ->where('title', 'like', "%{$term}%")
                    ->orWhere('content', 'like', "%{$term}%"));
            })
            ->when($f['category'] ?? null, function ($q, $slug) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $slug));
            })
            ->when($f['tag'] ?? null, function ($q, $slug) {
                $q->whereHas('tags', fn ($t) => $t->where('slug', $slug));
            });
    }

    public function isPublished(): bool
    {
        return $this->status === self::PUBLISHED;
    }

    public function isOwnedBy(?int $userId): bool
    {
        return $userId !== null && (int) $this->user_id === $userId;
    }

    public function imageUrl(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        return str_starts_with($this->file_path, 'http')
            ? $this->file_path
            : asset($this->file_path);
    }

    public static function uniqueSlug(
        string $title,
        ?int $ignoreId = null
    ): string
    {
        $base = Str::slug($title) ?: 'bai-viet';
        $slug = $base;
        $i = 2;

        while (static::withTrashed()->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
