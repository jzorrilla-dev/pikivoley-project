<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GameMatch;
use App\Models\MatchRule;
use App\Models\MatchScore;

class ScoreCalculationService
{
    public function isMatchWon(MatchScore $localScore, MatchScore $visitanteScore, MatchRule $rule): ?string
    {
        $pointsToWin = $rule->points_to_win;
        $winDifference = $rule->win_difference;
        $maxExtensions = $rule->max_extensions;

        $localPoints = $localScore->points;
        $visitantePoints = $visitanteScore->points;
        $localExtensions = $localScore->extensions_played;
        $visitanteExtensions = $visitanteScore->extensions_played;

        $totalLocal = $localPoints + ($localExtensions * $rule->extension_points);
        $totalVisitante = $visitantePoints + ($visitanteExtensions * $rule->extension_points);

        $totalExtensions = $localExtensions + $visitanteExtensions;

        if ($totalExtensions >= $maxExtensions) {
            return $this->determineWinnerBySuddenDeath($totalLocal, $totalVisitante);
        }

        if ($localPoints >= $pointsToWin && ($localPoints - $visitantePoints) >= $winDifference) {
            return 'local';
        }

        if ($visitantePoints >= $pointsToWin && ($visitantePoints - $localPoints) >= $winDifference) {
            return 'visitante';
        }

        return null;
    }

    protected function determineWinnerBySuddenDeath(int $localTotal, int $visitanteTotal): ?string
    {
        if ($localTotal > $visitanteTotal) {
            return 'local';
        }

        if ($visitanteTotal > $localTotal) {
            return 'visitante';
        }

        return null;
    }

    public function canAddExtension(MatchScore $localScore, MatchScore $visitanteScore, MatchRule $rule): bool
    {
        $maxExtensions = $rule->max_extensions;
        $pointsToWin = $rule->points_to_win;
        $winDifference = $rule->win_difference;

        $localExtensions = $localScore->extensions_played;
        $visitanteExtensions = $visitanteScore->extensions_played;

        if (($localExtensions + $visitanteExtensions) >= $maxExtensions) {
            return false;
        }

        $localPoints = $localScore->points;
        $visitantePoints = $visitanteScore->points;

        if ($localPoints < $pointsToWin && $visitantePoints < $pointsToWin) {
            return false;
        }

        $difference = abs($localPoints - $visitantePoints);
        if ($difference < $winDifference) {
            return true;
        }

        return false;
    }

    public function calculateWinner(GameMatch $match): ?int
    {
        /** @var \App\Models\MatchRule|null $rule */
        $rule = $match->rule;
        if (! $rule) {
            return null;
        }

        /** @var \Illuminate\Support\Collection $scores */
        $scores = $match->scores;
        /** @var \App\Models\MatchScore|null $localScore */
        $localScore = $scores->firstWhere('team_side', 'local');
        /** @var \App\Models\MatchScore|null $visitanteScore */
        $visitanteScore = $scores->firstWhere('team_side', 'visitante');

        if (! $localScore || ! $visitanteScore) {
            return null;
        }

        $winner = $this->isMatchWon($localScore, $visitanteScore, $rule);

        if ($winner === null) {
            return null;
        }

        /** @var \App\Models\MatchTeam|null $winningTeam */
        $winningTeam = $match->teams()->where('side', $winner)->first();

        return $winningTeam?->id;
    }

    public function isSuddenDeath(int $localExtensions, int $visitanteExtensions, int $maxExtensions): bool
    {
        return ($localExtensions + $visitanteExtensions) >= ($maxExtensions - 1);
    }

    public function getMatchStatus(GameMatch $match): array
    {
        /** @var \App\Models\MatchRule|null $rule */
        $rule = $match->rule;
        /** @var \Illuminate\Support\Collection $scores */
        $scores = $match->scores;
        /** @var \App\Models\MatchScore|null $localScore */
        $localScore = $scores->firstWhere('team_side', 'local');
        /** @var \App\Models\MatchScore|null $visitanteScore */
        $visitanteScore = $scores->firstWhere('team_side', 'visitante');

        if (! $rule || ! $localScore || ! $visitanteScore) {
            return [
                'is_finished' => false,
                'winner' => null,
                'in_extension' => false,
                'is_sudden_death' => false,
            ];
        }

        $winner = $this->isMatchWon($localScore, $visitanteScore, $rule);
        $inExtension = $this->canAddExtension($localScore, $visitanteScore, $rule);
        $isSuddenDeath = $this->isSuddenDeath(
            $localScore->extensions_played,
            $visitanteScore->extensions_played,
            $rule->max_extensions
        );

        return [
            'is_finished' => $winner !== null,
            'winner' => $winner,
            'in_extension' => $inExtension,
            'is_sudden_death' => $isSuddenDeath,
        ];
    }
}
