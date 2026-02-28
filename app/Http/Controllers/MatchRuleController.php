<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\GameMatch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MatchRuleController extends Controller
{
    public function update(Request $request, GameMatch $match): RedirectResponse
    {
        $validated = $request->validate([
            'modality' => ['required', 'in:1x1,2x2,3x3'],
            'max_touches' => ['required', 'in:2,3'],
            'serve_type' => ['required', 'in:foot_only,hand_allowed'],
            'points_to_win' => ['required', 'integer', 'min:1'],
            'win_difference' => ['required', 'integer', 'min:1'],
            'max_extensions' => ['sometimes', 'integer', 'min:1'],
            'extension_points' => ['sometimes', 'integer', 'min:1'],
            'additional_notes' => ['nullable', 'string'],
        ]);

        $match->rule->update($validated);

        return back()->with('success', 'Reglas actualizadas');
    }
}
