<?php

namespace Tests\Feature\Project;

use App\Models\Project;
use App\Models\User;
use Tests\TestCase;

class CreateTest extends TestCase
{
    private const ROTA = 'project.store';
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testFailAuth()
    {
        $data = Project::factory()->make();
        $response = $this->postJson(route(self::ROTA), $data->toArray());
        $response->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testFailRequiredValues()
    {
        $data = [
            'title' => null
        ];

        $response = $this->actingAs($this->user)->postJson(route(self::ROTA), $data);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title'
                ]
            ]);
    }

    public function testFailValuesLen()
    {
        $data = [
            'title' => str_pad('', 61, 'A')
        ];

        $response = $this->actingAs($this->user)->postJson(route(self::ROTA), $data);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title'
                ]
            ]);
    }

    public function testFailValuesType()
    {
        $data = [
            'title' => true
        ];

        $response = $this->actingAs($this->user)->postJson(route(self::ROTA), $data);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title'
                ]
            ]);
    }

    public function testSucess()
    {
        $data = Project::factory()->make();

        $response = $this->actingAs($this->user)->postJson(route(self::ROTA), $data->toArray());
        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'owner'
                ]
            ]);
    }
}
