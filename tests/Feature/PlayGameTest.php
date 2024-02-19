<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlayGameTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
/*     public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    } */



    public function testNonPlayerCannotPlayGame(){
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/players/{$user->id}/games");

        $response->assertStatus(403);
    }

    public function testNonAuthenticatedUserAttemptsToPlay(){
        $response = $this->postJson("/api/players/1/games");

        $response->assertStatus(401);
    }
    public function testRollExecutedByAuthenticatedPlayerTryingToAssignRollToAnotherPlayer(){
        $player1 = User::factory()->create();
        $player2 = User::factory()->create();

        $this->actingAs($player1);

        $response = $this->postJson("/api/players/{$player2->id}/games");

        $response->assertStatus(403);
    }
}
