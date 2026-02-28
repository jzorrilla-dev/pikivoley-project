<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nickname',
        'phone',
        'avatar',
        'reputation_score',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function createdMatches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'created_by');
    }

    public function refereeMatches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'referee_id');
    }

    public function captainTeams(): HasMany
    {
        return $this->hasMany(MatchTeam::class, 'captain_id');
    }

    public function matchTeamPlayers(): HasMany
    {
        return $this->hasMany(MatchTeamPlayer::class);
    }

    public function reputationEvents(): HasMany
    {
        return $this->hasMany(ReputationEvent::class);
    }

    public function refereeInvitations(): HasMany
    {
        return $this->hasMany(RefereeInvitation::class, 'referee_id');
    }

    public function matchTeams(): BelongsToMany
    {
        return $this->belongsToMany(MatchTeam::class, 'match_team_players')
            ->withPivot('is_captain', 'confirmed')
            ->withTimestamps();
    }

    public function getReputationLevelAttribute(): string
    {
        return match (true) {
            $this->reputation_score >= 100 => 'Experto',
            $this->reputation_score >= 50 => 'Confiable',
            $this->reputation_score >= 20 => 'Regular',
            $this->reputation_score >= 0 => 'Nuevo',
            default => 'Sin reputación',
        };
    }
}
