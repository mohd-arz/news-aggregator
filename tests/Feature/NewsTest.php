<?php

namespace Tests\Feature;

use App\Trait\ResponseTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use ResponseTrait;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        DB::beginTransaction();

        $user = \App\Models\User::factory()->create();
        $loginResponse = $this->post('api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $loginResponse->json('token');

        $response = $this->get('/api/v1/news',[
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);
        $response->assertJson(
            json_decode($this->successResponse('News Fetched Successfully', [])->getContent(), true)
        );
        

        DB::rollBack();
    }
}
