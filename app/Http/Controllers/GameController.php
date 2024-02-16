<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function playGame(Request $request, $id){

        //$user = Auth::user();
        $user = User::findOrFail($id);
        
        if ($request->user()->hasRole('player') && $user->id == $request->user()->id) {

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

        } else {
            return response()->json(['error' => 'No permission to make a roll.'], 403);
        }
    
    }

    public function deleteGames(Request $request,$id){
        $user = User::findOrFail($id);

        if ($request->user()->hasRole('player') && $user->id == $request->user()->id) {

            $user->games()->delete();
            return response()->json(['message' => 'Games deleted successfully'],200);

        } else {
            return response()->json(['error' => 'No permission to delete. '], 403);
        }
    }

    public function getPlayerGames(Request $request,$id){
        $user = User::findOrFail($id);

        if ($request->user()->hasRole('player') && $user->id == $request->user()->id) {

            $games = $user->games;
            return response()->json([$games],200);
            
        } else {
            return response()->json(['error' => 'No permission to get the list of rolls.'], 403);
        }
        
    }

}
