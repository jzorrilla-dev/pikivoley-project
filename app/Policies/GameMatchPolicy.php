<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\GameMatch;
use App\Models\User;

class GameMatchPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, GameMatch $gameMatch): bool
    {
        return $gameMatch->created_by === $user->id
            || $gameMatch->teams()->whereHas('players', fn ($q) => $q->where('user_id', $user->id))->exists()
            || $gameMatch->referee_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, GameMatch $gameMatch): bool
    {
        return $gameMatch->created_by === $user->id
            && $gameMatch->status === GameMatch::STATUS_PENDING;
    }

    public function delete(User $user, GameMatch $gameMatch): bool
    {
        return $gameMatch->created_by === $user->id
            && $gameMatch->status === GameMatch::STATUS_PENDING;
    }

    public function confirm(User $user, GameMatch $gameMatch): bool
    {
        return $gameMatch->teams()
            ->whereHas('players', fn ($q) => $q->where('user_id', $user->id)->where('is_captain', true))
            ->exists()
            && $gameMatch->status === GameMatch::STATUS_PENDING;
    }

    public function start(User $user, GameMatch $gameMatch): bool
    {
        return ($gameMatch->referee_id === $user->id
            || $gameMatch->teams()->whereHas('players', fn ($q) => $q->where('user_id', $user->id)->where('is_captain', true))->exists())
            && $gameMatch->status === GameMatch::STATUS_CONFIRMED;
    }

    public function finish(User $user, GameMatch $gameMatch): bool
    {
        return $gameMatch->referee_id === $user->id
            && $gameMatch->status === GameMatch::STATUS_IN_PROGRESS;
    }

    public function reportWinner(User $user, GameMatch $gameMatch): bool
    {
        return $gameMatch->teams()
            ->whereHas('players', fn ($q) => $q->where('user_id', $user->id)->where('is_captain', true))
            ->exists()
            && in_array($gameMatch->status, [GameMatch::STATUS_CONFIRMED, GameMatch::STATUS_IN_PROGRESS]);
    }
}
