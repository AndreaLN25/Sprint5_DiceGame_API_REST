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
            ]);

            $user = User::create([
                'name' => $request->input('name', 'Anonymous'),
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            if (!$user) {
                throw new \Exception('Error creating user');
            }

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
            return response()->json(['message' => 'Bye' . $user->name], 200);
        }
    }

    public function updateUser(Request $request, $id){
        try {
            $userId = Auth::user()->id;

            if ($userId != $id) {
                return response()->json(['error' => 'No permission to update.'], 403);
            }

            User::where('id', $userId)->update(['name'=>$request->name ? $request->name : 'Anonymous']);
            return response()->json(['message' => 'User name updated successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPlayerList(){
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
    
        return response()->json(['players' => $playerList]);
    }

    public function getAverageSuccessPercentage(){
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

        return response()->json(['average_success_rate_total_players' => $averageSuccessRateTotalPlayers, 'average_success_rate' => $averageSuccessRate]);

    }
}
