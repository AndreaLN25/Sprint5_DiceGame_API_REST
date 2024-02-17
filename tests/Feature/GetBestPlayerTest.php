<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetBestPlayerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
/*     public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    } */

    /*     public function setUp(): void {
        parent::setUp();

        // Ejecutar migraciones y seeders
        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    } */

    public function testAdminGetBestPlayer(){
        $admin = User::where('email', 'admin1@gmail.com')->first();

        $response = $this->actingAs($admin)->getJson('/api/players/ranking/winner');

        $response->assertStatus(200);
    }

    public function testNonAdminCantGetBestPlayer(){
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/players/ranking/winner');

        $response->assertStatus(403);
    }

    public function testUnauthorizedResponseWhenNonAdminAttemptsToGetWorstPlayer(){
        $response = $this->getJson('/api/players/ranking/winner');

        $response->assertStatus(401);
    }
}
