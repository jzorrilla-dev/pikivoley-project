<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchRule extends Model
{
    protected $fillable = [
        'match_id',
        'modality',
        'max_touches',
        'serve_type',
        'points_to_win',
        'win_difference',
        'max_extensions',
        'extension_points',
        'additional_notes',
    ];

    protected function casts(): array
    {
        return [
            'max_touches' => 'integer',
            'points_to_win' => 'integer',
            'win_difference' => 'integer',
            'max_extensions' => 'integer',
            'extension_points' => 'integer',
        ];
    }

    public const MODALITY_1X1 = '1x1';

    public const MODALITY_2X2 = '2x2';

    public const MODALITY_3X3 = '3x3';

    public const MAX_TOUCHES_2 = 2;

    public const MAX_TOUCHES_3 = 3;

    public const SERVE_TYPE_FOOT_ONLY = 'foot_only';

    public const SERVE_TYPE_HAND_ALLOWED = 'hand_allowed';

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class);
    }
}
