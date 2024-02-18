<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteGamesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
/*     public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    } */
    public function testUnauthorizedResponseWhenNonAuthenticatedUserAttemptsToDeleteGames(){
        $response = $this->deleteJson("/api/players/1/games");

        $response->assertStatus(401);
    }

    public function testPlayerCannotDeleteOtherPlayersGames(){
        $player1 = User::factory()->create();
        $player2 = User::factory()->create();

        $this->actingAs($player1);

        $player2->games()->createMany([
            ['dice1' => 1, 'dice2' => 2, 'totalSum' => 3, 'win' => true],
            ['dice1' => 3, 'dice2' => 4, 'totalSum' => 7, 'win' => false],
        ]);

        $response = $this->deleteJson("/api/players/{$player2->id}/games");

        $response->assertStatus(403);
    }
}
