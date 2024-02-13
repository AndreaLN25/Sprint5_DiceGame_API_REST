<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */



Route::post('/players', [UserController::class, 'registerUser']) ->name('registerUser'); // Creates a player
Route::post('/login', [UserController::class, 'login']) ->name('login');

Route::middleware('auth:api')->group(function () {
    Route::put('/players/{id}', [UserController::class, 'updateUser']) ->name('updateUser'); // Modifies the name of a player
    Route::post('/logout', [UserController::class, 'logout']) ->name('logout');


    Route::get('/players', [UserController::class, 'getPlayerList'])->name('getPlayerList')->middleware('auth:api'); // Returns the list of all players with their average success percentage
    Route::post('/players/{id}/games', [GameController::class, 'playGame']) ->name('playGame')->middleware('auth:api'); // A specific player makes a dice roll
    Route::delete('/players/{id}/games', [GameController::class, 'deleteGames']) ->name('deleteGames')->middleware('auth:api');// Deletes the rolls of a player
    Route::get('/players/{id}/games', [GameController::class, 'getPlayerGames']) ->name('getPlayerGames')->middleware('auth:api'); // Returns the list of rolls for a player
    Route::get('/players/ranking', [UserController::class, 'getAverageSuccessPercentage']) ->name('getAverageSuccessPercentage')->middleware('auth:api'); // Returns the average ranking of all players




/*     Route::middleware(['role:admin'])->group(function () {
        //Route::post('/assign-role', [UserController::class, 'assignRoleUser']);
        Route::get('/players', [UserController::class, 'getPlayerList'])->name('getPlayerList'); // Returns the list of all players with their average success percentage
        Route::get('/players/ranking', [UserController::class, 'getAverageSuccessPercentage']) ->name('getAverageSuccessPercentage'); // Returns the average ranking of all players
        Route::get('/players/ranking/loser', [UserController::class, 'getWorstPlayer']) ->name('getWorstPlayer'); // Returns the player with the worst success percentage
        Route::get('/players/ranking/winner', [UserController::class, 'getBestPlayer']) ->name('getBestPlayer'); // Returns the player with the best success percentage
    });

    Route::middleware('role:player')->group(function () {
        Route::post('/players/{id}/games', [GameController::class, 'playGame']) ->name('playGame'); // A specific player makes a dice roll
        Route::delete('/players/{id}/games', [GameController::class, 'deleteGames']) ->name('deleteGames');// Deletes the rolls of a player
        Route::get('/players/{id}/games', [GameController::class, 'getPlayerGames']) ->name('getPlayerGames'); // Returns the list of rolls for a player
    }); */
});