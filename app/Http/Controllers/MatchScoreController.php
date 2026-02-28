<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\MatchScore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MatchScoreController extends Controller
{
    public function updateScore(Request $request, GameMatch $match): RedirectResponse
    {
        $validated = $request->validate([
            'team_side' => ['required', 'in:local,visitante'],
            'points' => ['required', 'integer', 'min:0'],
        ]);

        MatchScore::updateOrCreate(
            [
                'match_id' => $match->id,
                'team_side' => $validated['team_side'],
            ],
            ['points' => $validated['points']]
        );

        return back()->with('success', 'Marcador actualizado');
    }

    public function addExtension(Request $request, GameMatch $match): RedirectResponse
    {
        $validated = $request->validate([
            'team_side' => ['required', 'in:local,visitante'],
        ]);

        $score = MatchScore::firstOrCreate(
            [
                'match_id' => $match->id,
                'team_side' => $validated['team_side'],
            ],
            ['points' => 0, 'extensions_played' => 0]
        );

        $score->increment('extensions_played');

        return back()->with('success', 'Extensión registrada');
    }
}
