<?php

namespace Tests\Feature\Task;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class CreateTest extends TestCase
{
    private const ROTA = 'task.store';
    private User $user;
    private Project $project;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->project = Project::factory()->create();
        $this->project->users()->attach($this->user->getKey(), ['owner' => true]);
    }

    public function testFailAuth()
    {
        $data = Task::factory()->make(['project_id' => $this->project->getKey()]);
        $response = $this->postJson(route(self::ROTA), $data->toArray());
        $response->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testFailRequiredValues()
    {
        $data = [
            'title'      => null,
            'project_id' => null,
        ];

        $response = $this->actingAs($this->user)->postJson(route(self::ROTA), $data);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title',
                    'project_id'
                ]
            ]);
    }

    public function testFailValuesLen()
    {
        $data = [
            'title' => str_pad('', 61, 'A'),
            'project_id' => $this->project->getKey()
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
            'title'       => true,
            'description' => true,
            'status'      => true,
            'deadline'    => true,
            'project_id' => 'String'
        ];

        $response = $this->actingAs($this->user)->postJson(route(self::ROTA), $data);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title',
                    'description',
                    'status',
                    'deadline',
                    'project_id'
                ]
            ]);
    }

    public function testSucess()
    {
        $data = Task::factory()->make(['project_id' => $this->project->getKey()]);

        $response = $this->actingAs($this->user)->postJson(route(self::ROTA), $data->toArray());
        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'deadline',
                    'project_id',
                    'project_name'
                ]
            ]);
    }
}
