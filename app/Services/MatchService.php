<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GameMatch;
use App\Models\MatchRule;
use App\Models\MatchTeam;
use App\Models\MatchTeamPlayer;
use App\Models\RefereeInvitation;
use Illuminate\Support\Facades\DB;

class MatchService
{
    public function __construct(
        protected ReputationService $reputationService,
    ) {}

    public function createMatch(array $data): GameMatch
    {
        return DB::transaction(function () use ($data) {
            $match = GameMatch::create([
                'created_by' => $data['created_by'],
                'location' => $data['location'],
                'scheduled_at' => $data['scheduled_at'],
                'bet_amount' => $data['bet_amount'],
                'status' => GameMatch::STATUS_PENDING,
            ]);

            MatchRule::create([
                'match_id' => $match->id,
                'modality' => $data['modality'],
                'max_touches' => $data['max_touches'],
                'serve_type' => $data['serve_type'],
                'points_to_win' => $data['points_to_win'],
                'win_difference' => $data['win_difference'],
                'max_extensions' => $data['max_extensions'] ?? 3,
                'extension_points' => $data['extension_points'] ?? 5,
            ]);

            MatchTeam::create([
                'match_id' => $match->id,
                'captain_id' => $data['captain_id'],
                'side' => MatchTeam::SIDE_LOCAL,
            ]);

            MatchTeam::create([
                'match_id' => $match->id,
                'captain_id' => $data['captain_id'],
                'side' => MatchTeam::SIDE_VISITANTE,
            ]);

            return $match;
        });
    }

    public function confirmMatch(GameMatch $match): bool
    {
        if ($match->status !== GameMatch::STATUS_PENDING) {
            return false;
        }

        $match->load(['teams.players']);
        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\MatchTeam> $teams */
        $teams = $match->teams;
        $allPlayersConfirmed = $teams->flatMap(function (MatchTeam $team) {
            return $team->players;
        })->every(fn (MatchTeamPlayer $player) => $player->confirmed);

        if ($allPlayersConfirmed) {
            $match->update(['status' => GameMatch::STATUS_CONFIRMED]);

            return true;
        }

        return false;
    }

    public function startMatch(GameMatch $match): bool
    {
        if ($match->status !== GameMatch::STATUS_CONFIRMED) {
            return false;
        }

        $match->update(['status' => GameMatch::STATUS_IN_PROGRESS]);

        return true;
    }

    public function finishMatch(GameMatch $match, int $winnerTeamId): bool
    {
        if ($match->status !== GameMatch::STATUS_IN_PROGRESS) {
            return false;
        }

        return DB::transaction(function () use ($match, $winnerTeamId) {
            $match->update([
                'winner_team_id' => $winnerTeamId,
                'status' => GameMatch::STATUS_FINISHED,
            ]);

            $this->reputationService->calculateForMatch($match);

            return true;
        });
    }

    public function inviteReferee(GameMatch $match, int $refereeId): RefereeInvitation
    {
        return RefereeInvitation::create([
            'match_id' => $match->id,
            'referee_id' => $refereeId,
            'status' => RefereeInvitation::STATUS_PENDING,
        ]);
    }

    public function addPlayerToTeam(MatchTeam $team, int $userId): MatchTeamPlayer
    {
        return MatchTeamPlayer::create([
            'match_team_id' => $team->id,
            'user_id' => $userId,
            'is_captain' => false,
            'confirmed' => false,
        ]);
    }

    public function removePlayerFromTeam(MatchTeamPlayer $player): bool
    {
        $player->delete();

        return true;
    }

    public function setCaptain(MatchTeam $team, MatchTeamPlayer $player): bool
    {
        DB::transaction(function () use ($team, $player) {
            MatchTeamPlayer::where('match_team_id', $team->id)->update(['is_captain' => false]);

            $player->update(['is_captain' => true]);
            $team->update(['captain_id' => $player->user_id]);
        });

        return true;
    }

    public function canStartMatch(GameMatch $match): bool
    {
        if ($match->status !== GameMatch::STATUS_CONFIRMED) {
            return false;
        }

        $match->load(['teams', 'rule']);
        /** @var \App\Models\MatchRule|null $rule */
        $rule = $match->rule;
        $requiredPlayers = match ($rule?->modality) {
            '1x1' => 1,
            '2x2' => 2,
            '3x3' => 3,
            default => 1,
        };

        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\MatchTeam> $teams */
        $teams = $match->teams;
        foreach ($teams as $team) {
            if ($team->players()->count() < $requiredPlayers) {
                return false;
            }
        }

        return true;
    }
}
