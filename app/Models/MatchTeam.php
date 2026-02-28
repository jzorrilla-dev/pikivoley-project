<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatchTeam extends Model
{
    protected $fillable = [
        'match_id',
        'captain_id',
        'side',
    ];

    public const SIDE_LOCAL = 'local';

    public const SIDE_VISITANTE = 'visitante';

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class);
    }

    public function captain(): BelongsTo
    {
        return $this->belongsTo(User::class, 'captain_id');
    }

    public function players(): HasMany
    {
        return $this->hasMany(MatchTeamPlayer::class);
    }

    public function score(): HasMany
    {
        return $this->hasMany(MatchScore::class, 'team_side', 'side');
    }
}
