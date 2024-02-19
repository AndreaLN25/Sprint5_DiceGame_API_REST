<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
   /*  public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    } */
    protected function setUp(): void{
        parent::setUp();

        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    }
    public function testUserRegister()
    {
        $userData = [
            'email' => 'userregister@gmail.com',
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
            'email' => 'userregisternopassword@gmail.com',
            //'password' => '12345678',
        ];

        $response = $this->json('POST', '/api/players', $userData);

        $response->assertStatus(500);
    }

    public function testRegisterUserWithoutName()
    {
        $userData = [
            'email' => 'registernoname@gmail.com',
            'password' => '12345678'
        ];

        $response = $this->postJson('/api/players', $userData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', ['name' => 'Anonymous']);
    }
    public function testRegisterUserWithDuplicateEmail()
    {
        User::factory()->create([
            'email' => 'duplicatename@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $userData = [
            'name' => 'Duplicatename',
            'email' => 'duplicatename@gmail.com',
            'password' => '12345678'
        ];

        $response = $this->postJson('/api/players', $userData);

        $response->assertStatus(500);

        $this->assertEquals(1, User::where('email', 'duplicatename@gmail.com')->count());
    }


}
