<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function playGame(Request $request, $id){

        $user = Auth::user();
        if ($user->id != $id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $dice1 = rand(1, 6);
        $dice2 = rand(1, 6);
        $totalSum = $dice1 + $dice2;

        $game = Game::create([
            'user_id' => $user->id,
            'dice1' => $dice1,
            'dice2' => $dice2,
            'totalSum' => $totalSum, 
            'win' => ($totalSum == 7 ? true : false),
        ]);

        $totalGames = Game::count();
        $wins = Game::where('win', true)->count();
        $successRate = ($totalGames > 0) ? ($wins / $totalGames) * 100 : 0;

        return response()->json(['game' => $game, 'success_rate' => $successRate], 201);
    
    }

}
