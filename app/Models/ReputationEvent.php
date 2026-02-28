<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReputationEvent extends Model
{
    protected $fillable = [
        'user_id',
        'match_id',
        'type',
        'points',
    ];

    protected function casts(): array
    {
        return [
            'points' => 'integer',
        ];
    }

    public const TYPE_WON = 'won';

    public const TYPE_LOST = 'lost';

    public const TYPE_DISPUTED = 'disputed';

    public const TYPE_PAID = 'paid';

    public const TYPE_NO_SHOW = 'no_show';

    public const POINTS_WON = 10;

    public const POINTS_LOST = -2;

    public const POINTS_DISPUTED = -15;

    public const POINTS_PAID = 5;

    public const POINTS_NO_SHOW = -20;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class);
    }

    public static function getPointsForType(string $type): int
    {
        return match ($type) {
            self::TYPE_WON => self::POINTS_WON,
            self::TYPE_LOST => self::POINTS_LOST,
            self::TYPE_DISPUTED => self::POINTS_DISPUTED,
            self::TYPE_PAID => self::POINTS_PAID,
            self::TYPE_NO_SHOW => self::POINTS_NO_SHOW,
            default => 0,
        };
    }
}
