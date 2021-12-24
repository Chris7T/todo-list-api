<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    private const ROTA = 'auth.register';

    public function testFailMaxLenghtValues()
    {
        $newData = User::factory()->make(['name' => str_pad('', 50, 'A')])->toArray();
        $newData['password'] = str_pad('', 50, 'A');
        $newData['password_confirmation'] = str_pad('', 50, 'A');

        $response = $this->postJson(route(self::ROTA), $newData);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'password',
                ],
            ]);
    }

    public function testFailMinLenghtValues()
    {
        $newData = User::factory()->make()->toArray();
        $newData['password'] = str_pad('', 7, 'A');
        $newData['password_confirmation'] = str_pad('', 7, 'A');

        $response = $this->postJson(route(self::ROTA), $newData);
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
        $newData = [
            'name' => null,
            'email' => null,
            'password' => null,
            'password_confirmation' => null,
        ];

        $response = $this->postJson(route(self::ROTA), $newData);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'email',
                    'password',
                ],
            ]);
    }

    public function testFailTypeValues()
    {
        $newData = [
            'name' => 12,
            'email' => 12,
            'password' => 12,
        ];

        $response = $this->postJson(route(self::ROTA), $newData);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'email',
                    'password',
                ],
            ]);
    }

    public function testFailEmailFormat()
    {
        $newData = User::factory()->make(['email' => str_pad('', 20, 'A')])->toArray();

        $response = $this->postJson(route(self::ROTA), $newData);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                ],
            ]);
    }
    public function testFailPassConfirmation()
    {
        $newData = User::factory()->make()->toArray();
        $newData['password'] = str_pad('', 10, 'A');

        $response = $this->postJson(route(self::ROTA), $newData);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'password',
                ],
            ]);
    }

    public function testSucess()
    {
        $newData = User::factory()->make()->toArray();
        $newData['password'] = str_pad('', 10, 'A');
        $newData['password_confirmation'] = str_pad('', 10, 'A');
        $response = $this->postJson(route(self::ROTA), $newData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
