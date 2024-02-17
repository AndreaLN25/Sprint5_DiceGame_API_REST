<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     */
/*     public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    } */
    public function testSuccessfulLogout(){
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->json('POST', 'api/logout');

        $response->assertStatus(200);
    }

    public function testUnauthenticatedLogout(){
        $response = $this->json('POST','api/logout');
        $response->assertStatus(401);
    }
}
