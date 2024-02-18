<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlayGameTest extends TestCase
{
    /**
     * A basic feature test example.
     */
/*     public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    } */

    public function testPlayerCanPlayGame(){
        $player = User::factory()->create();

        $response = $this->actingAs($player)->postJson("/api/players/{$player->id}/games");

        $response->assertStatus(201);
    }

    public function testNonPlayerCannotPlayGame(){
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/players/{$user->id}/games");

        $response->assertStatus(403);
    }

    public function testNonAuthenticatedUserAttemptsToPlay(){
        $response = $this->postJson("/api/players/1/games");

        $response->assertStatus(401);
    }
}
