<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    private const ROTA = 'auth.login';
    private const TEST_PASSWORD = 'Test Password';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testFailEmailFormat()
    {
        $loginData = [
            'email' => str_pad('', 20, 'A'),
            'password' => self::TEST_PASSWORD,
        ];

        $response = $this->postJson(route(self::ROTA), $loginData);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                ],
            ]);
    }

    public function testFailMaxLenghtValues()
    {
        $loginData = [
            'email' => $this->user->email,
            'password' => str_pad('', 50, 'A'),
            'password_confirmation' => str_pad('', 50, 'A'),
        ];

        $response = $this->postJson(route(self::ROTA), $loginData);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'password',
                ],
            ]);
    }

    public function testFailMinLenghtValues()
    {
        $loginData = [
            'email' => $this->user->email,
            'password' => str_pad('', 5, 'A'),
            'password_confirmation' => str_pad('', 5, 'A'),
        ];

        $response = $this->postJson(route(self::ROTA), $loginData);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'password',
                ],
            ]);
    }

    public function testFailRequiredValues()
    {
        $loginData = [
            'email' => null,
            'password' => null,
            'password_confirmation' => null,
        ];

        $response = $this->postJson(route(self::ROTA), $loginData);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                    'password',
                ],
            ]);
    }

    public function testFailTypeValues()
    {
        $loginData = [
            'email' => 12,
            'password' => 12,
            'password_confirmation' => 12,
        ];

        $response = $this->postJson(route(self::ROTA), $loginData);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                    'password',
                ],
            ]);
    }

    public function testSucess()
    {
        $loginData = [
            'email' => $this->user->email,
            'password' => self::TEST_PASSWORD,
            'password_confirmation' => self::TEST_PASSWORD
        ];

        $response = $this->postJson(route(self::ROTA), $loginData);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
            ]);
    }
}
