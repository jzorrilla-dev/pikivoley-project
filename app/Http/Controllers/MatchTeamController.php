<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\MatchTeam;
use App\Models\MatchTeamPlayer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MatchTeamController extends Controller
{
    public function addPlayer(Request $request, GameMatch $match, MatchTeam $team): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        MatchTeamPlayer::create([
            'match_team_id' => $team->id,
            'user_id' => $validated['user_id'],
            'is_captain' => false,
            'confirmed' => false,
        ]);

        return back()->with('success', 'Jugador agregado al equipo');
    }

    public function removePlayer(MatchTeam $team, MatchTeamPlayer $player): RedirectResponse
    {
        $player->delete();

        return back()->with('success', 'Jugador removido del equipo');
    }

    public function setCaptain(MatchTeam $team, MatchTeamPlayer $player): RedirectResponse
    {
        MatchTeamPlayer::where('match_team_id', $team->id)->update(['is_captain' => false]);

        $player->update(['is_captain' => true]);
        $team->update(['captain_id' => $player->user_id]);

        return back()->with('success', 'Nuevo capitán designado');
    }

    public function confirmParticipation(MatchTeamPlayer $player): RedirectResponse
    {
        $player->update(['confirmed' => true]);

        return back()->with('success', 'Confirmación registrada');
    }
}
