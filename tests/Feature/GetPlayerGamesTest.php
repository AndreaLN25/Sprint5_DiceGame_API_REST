<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetPlayerGamesTest extends TestCase
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


    public function testAdminCannotGetGames(){
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson("/api/players/1/games");

        $response->assertStatus(403);
    }

    public function testUnauthorizedWhenNotAuthenticated(){
        $response = $this->getJson("/api/players/1/games");

        $response->assertStatus(401);
    }
}
