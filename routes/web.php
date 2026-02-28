<?php

use App\Http\Controllers\GameMatchController;
use App\Http\Controllers\MatchDisputeController;
use App\Http\Controllers\MatchRuleController;
use App\Http\Controllers\MatchScoreController;
use App\Http\Controllers\MatchTeamController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RefereeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('matches', GameMatchController::class);

    Route::post('matches/{match}/confirm', [GameMatchController::class, 'confirm'])->name('matches.confirm');
    Route::post('matches/{match}/start', [GameMatchController::class, 'start'])->name('matches.start');
    Route::post('matches/{match}/finish', [GameMatchController::class, 'finish'])->name('matches.finish');

    Route::post('matches/{match}/teams/{team}/players', [MatchTeamController::class, 'addPlayer'])->name('teams.players.add');
    Route::delete('teams/players/{player}', [MatchTeamController::class, 'removePlayer'])->name('teams.players.remove');
    Route::post('teams/players/{player}/captain', [MatchTeamController::class, 'setCaptain'])->name('teams.players.captain');
    Route::post('teams/players/{player}/confirm', [MatchTeamController::class, 'confirmParticipation'])->name('teams.players.confirm');

    Route::put('matches/{match}/rules', [MatchRuleController::class, 'update'])->name('matches.rules.update');

    Route::put('matches/{match}/scores', [MatchScoreController::class, 'updateScore'])->name('matches.scores.update');
    Route::post('matches/{match}/scores/extension', [MatchScoreController::class, 'addExtension'])->name('matches.scores.extension');

    Route::post('matches/{match}/disputes', [MatchDisputeController::class, 'report'])->name('matches.disputes.report');
    Route::post('matches/{match}/disputes/{dispute}/resolve', [MatchDisputeController::class, 'resolve'])->name('matches.disputes.resolve');

    Route::post('matches/{match}/referee/invite', [RefereeController::class, 'invite'])->name('referee.invite');
    Route::post('referee/invitations/{invitation}/accept', [RefereeController::class, 'accept'])->name('referee.accept');
    Route::post('referee/invitations/{invitation}/reject', [RefereeController::class, 'reject'])->name('referee.reject');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
