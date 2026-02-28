<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GameMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'created_by',
        'referee_id',
        'winner_team_id',
        'status',
        'location',
        'lat',
        'lng',
        'scheduled_at',
        'bet_amount',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'lat' => 'decimal:8',
            'lng' => 'decimal:8',
            'bet_amount' => 'integer',
        ];
    }

    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_FINISHED = 'finished';

    public const STATUS_DISPUTED = 'disputed';

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function referee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referee_id');
    }

    public function winnerTeam(): BelongsTo
    {
        return $this->belongsTo(MatchTeam::class, 'winner_team_id');
    }

    public function teams(): HasMany
    {
        return $this->hasMany(MatchTeam::class);
    }

    public function rule(): HasOne
    {
        return $this->hasOne(MatchRule::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(MatchScore::class);
    }

    public function disputes(): HasMany
    {
        return $this->hasMany(MatchDispute::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(MatchCard::class);
    }

    public function reputationEvents(): HasMany
    {
        return $this->hasMany(ReputationEvent::class);
    }

    public function refereeInvitations(): HasMany
    {
        return $this->hasMany(RefereeInvitation::class);
    }

    public function isEditable(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPendingConfirmation(): bool
    {
        return $this->status === self::STATUS_PENDING || $this->status === self::STATUS_CONFIRMED;
    }
}
