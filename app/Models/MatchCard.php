<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchCard extends Model
{
    protected $fillable = [
        'match_id',
        'player_id',
        'card_type',
        'reason',
        'issued_by',
    ];

    public const CARD_TYPE_YELLOW = 'yellow';

    public const CARD_TYPE_RED = 'red';

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_id');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function isYellow(): bool
    {
        return $this->card_type === self::CARD_TYPE_YELLOW;
    }

    public function isRed(): bool
    {
        return $this->card_type === self::CARD_TYPE_RED;
    }
}
