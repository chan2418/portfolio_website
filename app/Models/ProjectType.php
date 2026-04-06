<?php

namespace App\Models;

use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover_image',
        'order_column',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->orderBy('order_column')
            ->orderBy('name');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function publishedProjects(): HasMany
    {
        return $this->hasMany(Project::class)
            ->where('status', PublishStatus::Published->value);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        $coverImage = (string) ($this->cover_image ?? '');

        if ($coverImage === '') {
            return null;
        }

        if (Str::startsWith($coverImage, ['http://', 'https://'])) {
            return $coverImage;
        }

        if (Str::startsWith($coverImage, '/')) {
            return url($coverImage);
        }

        return Storage::disk('public')->url($coverImage);
    }
}
