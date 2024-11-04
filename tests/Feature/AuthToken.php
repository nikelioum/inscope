<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTokenTest extends TestCase
{

    public function test_authenticates_a_user_and_issues_a_token()
    {

        // Attempt to authenticate and retrieve a token
        $response = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        // Assert that the response is successful and contains a token
        $response->assertStatus(200)->assertJsonStructure(['token']);
    }
}
