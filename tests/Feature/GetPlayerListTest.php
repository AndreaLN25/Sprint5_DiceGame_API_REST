<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class GetPlayerListTest extends TestCase
{
    /**
     * A basic feature test example.
     */
/*     public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    } */

/*     protected function setUp(): void{
        parent::setUp();

        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    } */

    public function testAdminCanGetPlayerList()
    {
        $admin = User::where('email', 'admin1@gmail.com')->first();
        $player1 = User::where('email', 'player1@gmail.com')->first();

        $player1->games()->create(['win' => true]);
        $player1->games()->create(['win' => false]);

        $response = $this->actingAs($admin)->getJson('/api/players');

        $response->assertStatus(200);
        $response->assertJsonStructure(['players']);
        $response->assertJsonFragment(['name' => 'player1']);
    }


    public function testNonAdminCannotGetPlayerList()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/players');

        $response->assertStatus(403);
    }

    public function testPlayerListIsEmptyWhenNoPlayersExist()
    {
        $admin = User::where('email', 'admin1@gmail.com')->first();

        $response = $this->actingAs($admin)->getJson('/api/players');

        $response->assertStatus(200);
    }

}
