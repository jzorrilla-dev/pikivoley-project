<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GameMatch;
use App\Models\MatchTeam;
use App\Models\ReputationEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReputationService
{
    public function calculateForMatch(GameMatch $match): void
    {
        if (! $match->winner_team_id || $match->status !== GameMatch::STATUS_FINISHED) {
            return;
        }

        $match->load(['teams.players']);

        /** @var MatchTeam|null $winningTeam */
        $winningTeam = $match->teams()->find($match->winner_team_id);
        /** @var MatchTeam|null $losingTeam */
        $losingTeam = $match->teams()->where('id', '!=', $match->winner_team_id)->first();

        if (! $winningTeam || ! $losingTeam) {
            return;
        }

        DB::transaction(function () use ($match, $winningTeam, $losingTeam) {
            $this->awardPoints($winningTeam, $match, ReputationEvent::TYPE_WON);
            $this->deductPoints($losingTeam, $match, ReputationEvent::TYPE_LOST);

            $this->checkAndPenalizeNoShows($match);
        });
    }

    protected function awardPoints(MatchTeam $team, GameMatch $match, string $type): void
    {
        $points = ReputationEvent::getPointsForType($type);

        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\MatchTeamPlayer> $players */
        $players = $team->players;
        foreach ($players as $player) {
            ReputationEvent::create([
                'user_id' => $player->user_id,
                'match_id' => $match->id,
                'type' => $type,
                'points' => $points,
            ]);

            $this->updateUserReputation($player->user_id, $points);
        }
    }

    protected function deductPoints(MatchTeam $team, GameMatch $match, string $type): void
    {
        $points = ReputationEvent::getPointsForType($type);

        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\MatchTeamPlayer> $players */
        $players = $team->players;
        foreach ($players as $player) {
            ReputationEvent::create([
                'user_id' => $player->user_id,
                'match_id' => $match->id,
                'type' => $type,
                'points' => $points,
            ]);

            $this->updateUserReputation($player->user_id, $points);
        }
    }

    protected function updateUserReputation(int $userId, int $points): void
    {
        $user = User::find($userId);
        if (! $user) {
            return;
        }

        $newScore = max(0, $user->reputation_score + $points);
        $user->update(['reputation_score' => $newScore]);
    }

    protected function checkAndPenalizeNoShows(GameMatch $match): void
    {
        $match->load(['teams.players']);

        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\MatchTeam> $teams */
        $teams = $match->teams;
        foreach ($teams as $team) {
            /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\MatchTeamPlayer> $players */
            $players = $team->players;
            foreach ($players as $player) {
                $hasPlayed = ReputationEvent::where('user_id', $player->user_id)
                    ->where('match_id', $match->id)
                    ->exists();

                if (! $hasPlayed) {
                    $this->penalizeNoShow($player->user_id, $match);
                }
            }
        }
    }

    public function penalizeNoShow(int $userId, GameMatch $match): void
    {
        $points = ReputationEvent::getPointsForType(ReputationEvent::TYPE_NO_SHOW);

        ReputationEvent::create([
            'user_id' => $userId,
            'match_id' => $match->id,
            'type' => ReputationEvent::TYPE_NO_SHOW,
            'points' => $points,
        ]);

        $this->updateUserReputation($userId, $points);
    }

    public function markAsPaid(int $userId, GameMatch $match): void
    {
        $points = ReputationEvent::getPointsForType(ReputationEvent::TYPE_PAID);

        ReputationEvent::create([
            'user_id' => $userId,
            'match_id' => $match->id,
            'type' => ReputationEvent::TYPE_PAID,
            'points' => $points,
        ]);

        $this->updateUserReputation($userId, $points);
    }

    public function handleDispute(GameMatch $match): void
    {
        $losingPoints = ReputationEvent::getPointsForType(ReputationEvent::TYPE_DISPUTED);

        $match->load(['teams.players']);

        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\MatchTeam> $teams */
        $teams = $match->teams;
        foreach ($teams as $team) {
            /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\MatchTeamPlayer> $players */
            $players = $team->players;
            foreach ($players as $player) {
                ReputationEvent::create([
                    'user_id' => $player->user_id,
                    'match_id' => $match->id,
                    'type' => ReputationEvent::TYPE_DISPUTED,
                    'points' => $losingPoints,
                ]);

                $this->updateUserReputation($player->user_id, $losingPoints);
            }
        }
    }

    public function getUserReputationHistory(User $user, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $user->reputationEvents()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getReputationLevel(int $score): string
    {
        return match (true) {
            $score >= 100 => 'Experto',
            $score >= 50 => 'Confiable',
            $score >= 20 => 'Regular',
            $score >= 0 => 'Nuevo',
            default => 'Sin reputación',
        };
    }
}
