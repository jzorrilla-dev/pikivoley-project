<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Services\MatchService;
use App\Services\ScoreCalculationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameMatchController extends Controller
{
    public function __construct(
        protected MatchService $matchService,
        protected ScoreCalculationService $scoreService,
    ) {}

    public function index(): View
    {
        $matches = GameMatch::with(['creator', 'teams.captain', 'rule'])
            ->where('created_by', auth()->id())
            ->orWhereHas('teams.players', fn ($query) => $query->where('user_id', auth()->id()))
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);

        return view('matches.index', compact('matches'));
    }

    public function create(): View
    {
        return view('matches.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'location' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'bet_amount' => ['required', 'integer', 'min:0'],
            'modality' => ['required', 'in:1x1,2x2,3x3'],
            'max_touches' => ['required', 'in:2,3'],
            'serve_type' => ['required', 'in:foot_only,hand_allowed'],
            'points_to_win' => ['required', 'integer', 'min:1'],
            'win_difference' => ['required', 'integer', 'min:1'],
        ]);

        $data = array_merge($validated, [
            'created_by' => auth()->id(),
        ]);

        $match = $this->matchService->createMatch($data);

        return redirect()->route('matches.show', $match)->with('success', 'Partido creado exitosamente');
    }

    public function show(GameMatch $match): View
    {
        $match->load(['creator', 'referee', 'teams.captain', 'teams.players.user', 'rule', 'scores', 'disputes', 'cards']);

        $matchStatus = $this->scoreService->getMatchStatus($match);

        return view('matches.show', compact('match', 'matchStatus'));
    }

    public function edit(GameMatch $match): View
    {
        $this->authorize('update', $match);

        return view('matches.edit', compact('match'));
    }

    public function update(Request $request, GameMatch $match): RedirectResponse
    {
        $this->authorize('update', $match);

        $validated = $request->validate([
            'location' => ['sometimes', 'string', 'max:255'],
            'scheduled_at' => ['sometimes', 'date'],
            'bet_amount' => ['sometimes', 'integer', 'min:0'],
        ]);

        $match->update($validated);

        return redirect()->route('matches.show', $match)->with('success', 'Partido actualizado');
    }

    public function destroy(GameMatch $match): RedirectResponse
    {
        $this->authorize('delete', $match);

        $match->delete();

        return redirect()->route('matches.index')->with('success', 'Partido eliminado');
    }

    public function confirm(GameMatch $match): RedirectResponse
    {
        $this->authorize('confirm', $match);

        $confirmed = $this->matchService->confirmMatch($match);

        if ($confirmed) {
            return back()->with('success', 'Partido confirmado');
        }

        return back()->with('warning', 'No todos los jugadores han confirmado su participación');
    }

    public function start(GameMatch $match): RedirectResponse
    {
        $this->authorize('start', $match);

        if (! $this->matchService->canStartMatch($match)) {
            return back()->with('error', 'El partido no tiene suficientes jugadores confirmados');
        }

        $this->matchService->startMatch($match);

        return back()->with('success', 'Partido iniciado');
    }

    public function finish(Request $request, GameMatch $match): RedirectResponse
    {
        $this->authorize('finish', $match);

        $validated = $request->validate([
            'winner_team_id' => ['required', 'exists:match_teams,id'],
        ]);

        $this->matchService->finishMatch($match, (int) $validated['winner_team_id']);

        return back()->with('success', 'Partido terminado. Puntos de reputación actualizados.');
    }
}
