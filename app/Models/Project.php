<?php

namespace App\Models;

use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'project_type_id',
        'title',
        'slug',
        'client_name',
        'industry',
        'summary',
        'challenge',
        'solution',
        'results',
        'tech_stack',
        'metrics',
        'cover_image',
        'project_url',
        'is_featured',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'tech_stack' => 'array',
            'metrics' => 'array',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Project $project): void {
            if ($project->status === PublishStatus::Published->value && blank($project->published_at)) {
                $project->published_at = now();
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PublishStatus::Published->value);
    }

    public function projectType(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class);
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

    public function getProjectTypeLabelAttribute(): string
    {
        return (string) ($this->projectType?->name ?: $this->industry ?: 'General');
    }
}
