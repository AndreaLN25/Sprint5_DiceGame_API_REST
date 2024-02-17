<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class rankingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
/*     public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    } */

/*     public function setUp(): void{
        parent::setUp();

        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    } */

    public function testRankingAdmin(){
    $admin = User::where('email', 'admin1@gmail.com')->first();

    $response = $this->actingAs($admin)->getJson('api/players/ranking');

    $response->assertStatus(200);
}

    public function testRankingPlayer(){
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/players/ranking');

        $response->assertStatus(403);
    }

    public function testRankingUnauthenticatedUser()
    {
        $response = $this->json('GET', 'api/players/ranking');
        $response->assertStatus(401);
    }
}

