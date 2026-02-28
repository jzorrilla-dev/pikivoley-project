<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchScore extends Model
{
    protected $fillable = [
        'match_id',
        'team_side',
        'points',
        'extensions_played',
    ];

    protected function casts(): array
    {
        return [
            'points' => 'integer',
            'extensions_played' => 'integer',
        ];
    }

    public const TEAM_SIDE_LOCAL = 'local';

    public const TEAM_SIDE_VISITANTE = 'visitante';

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class);
    }
}
