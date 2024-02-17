<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
/*     public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    } */
    public function testLoginUser(){

        $user = User::factory()->create([
            'email' => 'testlogin@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $loginData = [
            'email' => 'testlogin@gmail.com',
            'password' => '12345678',
        ];

        $response = $this->postJson('/api/login', $loginData);
        $response->assertStatus(200);

    }

    public function testLoginWithInvalidCredentials()
    {
        $loginData = ['email' => 'testLoginInvalid@example.com', 'password' => 'invalidpassword'];
        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(422);
    }

    public function testLoginWithoutEmail(){
        $response = $this->postJson('/api/login', ['password' => '12345678']);

        $response->assertStatus(422);
    }

    public function testLoginWithoutPassword(){
        $response = $this->postJson('/api/login', ['email' => 'usernopassword@gmail.com']);

        $response->assertStatus(422);
    }

    public function testLoginWithNonExistentUser()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'noregistered@gmail.com',
            'password' => '12345678',
        ]);

        $response->assertStatus(422);
    }


}
