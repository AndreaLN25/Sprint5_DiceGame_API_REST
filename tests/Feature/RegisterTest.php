<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     */
   /*  public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    } */
    public function testUserRegister()
    {
        $userData = [
            'email' => 'usertest01@gmail.com',
            'password' => '12345678',
        ];

        $response = $this->json('POST', '/api/players', $userData);

        $response->assertStatus(201);
    }

    public function testUserRegisterWithoutEmail(){
        $userData = [
            //'email' => 'usertest1@gmail.com',
            'password' => '12345678',
        ];

        $response = $this->json('POST', '/api/players', $userData);

        $response->assertStatus(500);
    }

    public function testUserRegisterWithoutPassword(){
        $userData = [
            'email' => 'usertest1@gmail.com',
            //'password' => '12345678',
        ];

        $response = $this->json('POST', '/api/players', $userData);

        $response->assertStatus(500);
    }

    public function testRegisterUserWithoutName()
    {
        $userData = [
            'email' => 'test0@gmail.com',
            'password' => '12345678'
        ];

        $response = $this->postJson('/api/players', $userData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', ['name' => 'Anonymous']);
    }
    public function testRegisterUserWithDuplicateEmail()
    {
        User::factory()->create([
            'email' => 'test1@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $userData = [
            'name' => 'TestUser',
            'email' => 'test1@gmail.com',
            'password' => '12345678'
        ];

        $response = $this->postJson('/api/players', $userData);

        $response->assertStatus(500);

        $this->assertEquals(1, User::where('email', 'test1@gmail.com')->count());
    }


}
