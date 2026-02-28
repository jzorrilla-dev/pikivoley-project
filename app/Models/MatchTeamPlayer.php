<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchTeamPlayer extends Model
{
    protected $fillable = [
        'match_team_id',
        'user_id',
        'is_captain',
        'confirmed',
    ];

    protected function casts(): array
    {
        return [
            'is_captain' => 'boolean',
            'confirmed' => 'boolean',
        ];
    }

    public function matchTeam(): BelongsTo
    {
        return $this->belongsTo(MatchTeam::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
