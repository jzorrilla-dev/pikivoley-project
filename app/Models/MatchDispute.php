<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchDispute extends Model
{
    protected $fillable = [
        'match_id',
        'reported_by',
        'claimed_winner',
        'reason',
    ];

    public const CLAIMED_WINNER_LOCAL = 'local';

    public const CLAIMED_WINNER_VISITANTE = 'visitante';

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
