<?php

namespace App\Models;

use App\Enums\LeadStage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadInquiry extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'company',
        'budget',
        'service_interest',
        'project_timeline',
        'message',
        'source',
        'stage',
        'status_note',
        'contacted_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'contacted_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::updated(function (LeadInquiry $lead): void {
            if (! $lead->wasChanged('stage')) {
                return;
            }

            $lead->activities()->create([
                'actor_id' => auth()->id(),
                'activity_type' => 'stage_changed',
                'note' => sprintf('Lead stage changed from %s to %s.', $lead->getOriginal('stage'), $lead->stage),
                'payload' => [
                    'from' => $lead->getOriginal('stage'),
                    'to' => $lead->stage,
                ],
            ]);

            if ($lead->stage === LeadStage::Contacted->value && is_null($lead->contacted_at)) {
                $lead->forceFill(['contacted_at' => now()])->saveQuietly();
            }
        });
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class);
    }
}
