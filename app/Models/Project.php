<?php

namespace App\Models;

use App\Enums\PublishStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
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
}
