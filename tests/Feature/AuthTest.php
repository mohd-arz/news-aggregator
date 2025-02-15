<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

     /** @test */
    public function user_register(): void
    {
        DB::beginTransaction();
        $response = $this->post('api/v1/register', [
            'name' => 'Arsh',
            'email' => 'mohammedarsh75@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'User Register Successfully']);
        DB::rollBack();
    }

    /** @test */
    public function user_login(){
        DB::beginTransaction();
        $user = \App\Models\User::factory()->create();
        $response = $this->post('api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'User Login Successfully']);
        DB::rollBack();
    }

    /** @test */
    public function user_logout(){
        DB::beginTransaction();
        $user = \App\Models\User::factory()->create();
        $loginResponse = $this->post('api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $loginResponse->json('token');

        $response = $this->post('api/v1/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'User Logout Successfully']);
        DB::rollBack();
    }
}
