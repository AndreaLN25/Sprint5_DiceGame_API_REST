<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UpdateTest extends TestCase
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

    protected function setUp(): void{
        parent::setUp();

        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    }
    public function testAdminCanUpdateUser(){
        $admin = User::where('email', 'admin1@gmail.com')->first();
        $userToUpdate = User::factory()->create(['name' => 'Name1']);

        $response = $this->actingAs($admin)->putJson("/api/players/{$userToUpdate->id}", ['name' => 'Name2']);

        $response->assertStatus(200);
        $this->assertEquals('Name2', $userToUpdate->fresh()->name);
    }

    public function testNonAdminCannotUpdateOtherUsers(){
        $nonAdmin = User::factory()->create(['email' => 'nonadmin@gmail.com', 'password' => bcrypt('12345678')]);
        $userToUpdate = User::factory()->create(['name' => 'Name1']);

        $response = $this->actingAs($nonAdmin)->putJson("/api/players/{$userToUpdate->id}", ['name' => 'Name2']);

        $response->assertStatus(403);
        $this->assertEquals('Name1', $userToUpdate->fresh()->name);
    }



    public function testUserCannotUpdateOtherUserProfiles(){
        $user1 = User::factory()->create();
        $user2 = User::factory()->create(['name' => 'Name1']);

        $response = $this->actingAs($user1)->putJson("/api/players/{$user2->id}", ['name' => 'Name2']);

        $response->assertStatus(403);
        $this->assertEquals('Name1', $user2->fresh()->name);
    }

    public function testUnauthorizedUserCannotUpdateAnyProfile(){
    $user = User::factory()->create();
    $otherUser = User::factory()->create(['name' => 'Name1']);

    $response = $this->putJson("/api/players/{$otherUser->id}", ['name' => 'Name2']);

    $response->assertStatus(401);
    $this->assertEquals('Name1', $otherUser->fresh()->name);
}


}
