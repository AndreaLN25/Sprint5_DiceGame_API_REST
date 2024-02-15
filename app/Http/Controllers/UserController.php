<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    

    public function registerUser(Request $request){
        try {
            $this->validate($request, [
                //'name' => 'required|min:4',
                'email' => 'required|email',
                'password' => 'required|min:8',
                //'role' => 'required|in:admin,player',   
            ]);

            $user = User::create([
                'name' => $request->input('name', 'Anonymous'),
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ])->assignRole('player');

            if (!$user) {
                throw new \Exception('Error creating user');
            }

            //$user->assignRole($request->role);

            $registerMessage ='Register completed';

            return response()->json(['message' => $registerMessage, 'user' => $user], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request){
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Incorrect credentials.'], 422);
            }

            $token = $user->createToken('API Token')->accessToken;
            return response(['message' => 'Hello ' . ($user->name), 'token' => $token, 'user' => $user], 200);


        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } 
    }

    public function logout(){ 
        $user = Auth::user();

        if ($user) {
            $user->tokens->each->revoke();
            return response()->json(['message' => 'Bye ' . $user->name], 200);
        }
    }

    public function updateUser(Request $request, $id){
        try {
            $user = User::findOrFail($id);

            if ($request->user()->hasRole('admin')) {

                $user->update(['name' => $request->name ?: 'Anonymous']);
                return response()->json(['message' => 'User name updated successfully'], 200);

            } else {
                return response()->json(['message' => 'No permission to update.'], 403);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPlayerList(Request $request){
        if ($request->user()->hasRole('admin')) {

            $players = User::all();
            $playerList = [];

            foreach ($players as $player) {
                $totalGames = $player->games()->count();
                $wins = $player->games()->where('win', true)->count();
                $successRate = ($totalGames > 0) ? ($wins / $totalGames) * 100 : 0;
        
                $playerList[] = [
                    'name' => $player->name,
                    'success_rate' => $successRate,
                ];
            }
            return response()->json(['players' => $playerList],200);

        }else{
            return response()->json(['message' => 'You are not authorized to get the player list.'], 403);
        }
    }

    public function getAverageSuccessPercentage(Request $request){
        if ($request->user()->hasRole('admin') || $request->user()->hasRole('player')) {

            $players = User::all();
            $totalPlayers = count($players);
            $totalSuccessRate = 0;
            $averageSuccessRateTotalPlayers = [];
        
            foreach ($players as $player) {
                $totalGames = $player->games()->count();
                $wins = $player->games()->where('win', true)->count();
                $successRate = ($totalGames > 0) ? ($wins / $totalGames) * 100 : 0;
                $totalSuccessRate += $successRate;


                $averageSuccessRateTotalPlayers[] = [
                    'name' => $player->name,
                    'average_success_rate' => $successRate,
                    'total_games' => $totalGames,
                ];
            }

            $averageSuccessRate = ($totalPlayers > 0) ? $totalSuccessRate / $totalPlayers : 0;


            $averageSuccessRateTotalPlayers = collect($averageSuccessRateTotalPlayers)
            ->sortBy('total_games')
            ->sortByDesc('average_success_rate')
            ->values()
            ->all();

            return response()->json(['Ranking by average_success_rate_total_players' => $averageSuccessRateTotalPlayers, 'average_success_rate' => $averageSuccessRate],200);
        
        } else {
            return response()->json(['message' => 'You are not authorized to get the average success percentage.'], 403);
        }
    }

    public function getWorstPlayer(Request $request){
        if ($request->user()->hasRole('admin')) {

            $players = User::all();
            $worstPlayer = null;
            $lowestSuccessRate = 100; 
        
            foreach ($players as $player) {
                $totalGames = $player->games()->count();
                $wins = $player->games()->where('win', true)->count();
                $successRate = ($totalGames > 0) ? ($wins / $totalGames) * 100 : 0;
        
                if ($successRate < $lowestSuccessRate) {
                    $lowestSuccessRate = $successRate;
                    $worstPlayer = [
                        'name' => $player->name,
                        'average_success_rate' => $successRate,
                        'total_games' => $totalGames,
                    ];
                }
            }
        
            return response()->json(['worst_player' => $worstPlayer],200);

        } else {
            return response()->json(['message' => 'You are not authorized to get the worst player.'], 403);
        }
    }
    
    public function getBestPlayer(Request $request){
        if ($request->user()->hasRole('admin')) {

            $players = User::all();
            $bestPlayer = null;
            $highestSuccessRate = 0; 

            foreach ($players as $player) {
                $totalGames = $player->games()->count();
                $wins = $player->games()->where('win', true)->count();
                $successRate = ($totalGames > 0) ? ($wins / $totalGames) * 100 : 0;
        
                if ($successRate > $highestSuccessRate) {
                    $highestSuccessRate = $successRate;
                    $bestPlayer = [
                        'name' => $player->name,
                        'average_success_rate' => $successRate,
                        'total_games' => $totalGames,
                    ];
                }
            }
        
            return response()->json(['best_player' => $bestPlayer],200);
        }else {
        return response()->json(['message' => 'You are not authorized to get the best player.'], 403);
        }
    }
}
