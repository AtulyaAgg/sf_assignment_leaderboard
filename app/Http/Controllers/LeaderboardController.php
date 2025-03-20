<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\GenerateQrCodeJob;
use Illuminate\Routing\Controller;

class LeaderboardController extends Controller {
    public function getLeaderboard() {
        return response()->json(User::orderByDesc('points')->get());
    }

    public function addUser(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'address' => 'required|string'
        ]);
    
        $user = User::create($validated);
        
        GenerateQrCodeJob::dispatch($user);
    
        return response()->json($user, 201);
    }

    public function deleteUser($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }

    public function updatePoints($id, Request $request) {
        $validated = $request->validate(['points' => 'required|integer']);
        $user = User::findOrFail($id);
        $user->update(['points' => $validated['points']]);
        return response()->json($user);
    }

    public function getUserDetails($id) {

        $user = User::findOrFail($id);
        return response()->json([
            'name' => $user->name,
            'age' => $user->age,
            'points' => $user->points,
            'address' => $user->address,
            'qr_code' => $user->qr_code ? asset("storage/{$user->qr_code}") : null,
        ]);
    }

    public function resetScores() {
        User::query()->update(['points' => 0]);
        return response()->json(['message' => 'Scores reset']);
    }

    public function getUsersGroupedByScore() {
        $users = User::all()->groupBy('points')->map(function ($group) {
            return [
                'names' => $group->pluck('name'),
                'average_age' => round($group->avg('age'))
            ];
        });

        return response()->json($users);
    }
}

