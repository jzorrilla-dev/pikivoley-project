<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\MatchDispute;
use App\Services\ReputationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MatchDisputeController extends Controller
{
    public function __construct(
        protected ReputationService $reputationService,
    ) {}

    public function report(Request $request, GameMatch $match): RedirectResponse
    {
        $validated = $request->validate([
            'claimed_winner' => ['required', 'in:local,visitante'],
            'reason' => ['required', 'string'],
        ]);

        MatchDispute::create([
            'match_id' => $match->id,
            'reported_by' => auth()->id(),
            'claimed_winner' => $validated['claimed_winner'],
            'reason' => $validated['reason'],
        ]);

        $match->update(['status' => GameMatch::STATUS_DISPUTED]);

        return back()->with('warning', 'Disputa reportada. El partido está en revisión.');
    }

    public function resolve(Request $request, GameMatch $match, MatchDispute $dispute): RedirectResponse
    {
        $validated = $request->validate([
            'resolution' => ['required', 'in:local,visitante,null'],
        ]);

        if ($validated['resolution']) {
            $winningTeam = $match->teams()->where('side', $validated['resolution'])->first();
            $winnerId = $winningTeam instanceof \App\Models\MatchTeam ? $winningTeam->id : null;
            $match->update([
                'winner_team_id' => $winnerId,
                'status' => GameMatch::STATUS_FINISHED,
            ]);

            $this->reputationService->handleDispute($match);
        } else {
            $match->update(['status' => GameMatch::STATUS_CONFIRMED]);
        }

        $dispute->delete();

        return back()->with('success', 'Disputa resuelta');
    }
}
