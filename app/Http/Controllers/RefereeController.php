<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\RefereeInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RefereeController extends Controller
{
    public function invite(Request $request, GameMatch $match): RedirectResponse
    {
        $validated = $request->validate([
            'referee_id' => ['required', 'exists:users,id'],
        ]);

        RefereeInvitation::create([
            'match_id' => $match->id,
            'referee_id' => $validated['referee_id'],
            'status' => RefereeInvitation::STATUS_PENDING,
        ]);

        return back()->with('success', 'Invitación enviada al árbitro');
    }

    public function accept(RefereeInvitation $invitation): RedirectResponse
    {
        $invitation->update(['status' => RefereeInvitation::STATUS_ACCEPTED]);

        $invitation->match->update(['referee_id' => $invitation->referee_id]);

        return redirect()->route('dashboard')->with('success', 'Has aceptado arbitrar el partido');
    }

    public function reject(RefereeInvitation $invitation): RedirectResponse
    {
        $invitation->update(['status' => RefereeInvitation::STATUS_REJECTED]);

        return redirect()->route('dashboard')->with('info', 'Invitación rechazada');
    }
}
