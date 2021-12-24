<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    private const ROTA = 'auth.logout';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testFailUnauth()
    {
        $token = 'Fake Token';

        $response = $this->withToken($token)->getJson(route(self::ROTA));
        $response->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testSucess()
    {
        $token = $this->user->createToken('Hospital-Test')->plainTextToken;

        $response = $this->withToken($token)->getJson(route(self::ROTA));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
