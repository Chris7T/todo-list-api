<?php

namespace Tests\Feature\Project;

use App\Models\Project;
use App\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    private const ROTA = 'project.update';
    private const INVALID_ID = 0;
    private User $user;
    private Project $project;
    private Project $projectNoPermission;

    public function setUp(): void
    {
        parent::setUp();
        $this->user    = User::factory()->create();
        $this->project = Project::factory()->create();
        $this->project->users()->attach($this->user->getKey(), ['owner' => true]);
        $this->projectNoPermission = Project::factory()->create();
    }

    public function testFailAuth()
    {
        $data = Project::factory()->make();
        $response = $this->putJson(route(self::ROTA, $this->project->getKey()), $data->toArray());
        $response->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testFailInvalidProject()
    {
        $data = Project::factory()->make();
        $response = $this->actingAs($this->user)->putJson(route(self::ROTA, self::INVALID_ID), $data->toArray());
        $response->assertStatus(404)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testFailRequiredValues()
    {
        $data = [
            'title' => null
        ];

        $response = $this->actingAs($this->user)->putJson(route(self::ROTA, $this->project->getKey()), $data);
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

        $response = $this->actingAs($this->user)->putJson(route(self::ROTA, $this->project->getKey()), $data);
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

        $response = $this->actingAs($this->user)->putJson(route(self::ROTA, $this->project->getKey()), $data);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title'
                ]
            ]);
    }

    public function testFailNotOwner()
    {
        $data = Project::factory()->make()->toArray();

        $response = $this->actingAs($this->user)->putJson(route(self::ROTA, $this->projectNoPermission->getKey()), $data);
        $response->assertStatus(403)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function testSucess()
    {
        $data = Project::factory()->make();

        $response = $this->actingAs($this->user)->putJson(route(self::ROTA, $this->project->getKey()), $data->toArray());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'owner'
                ]
            ]);
    }
}
