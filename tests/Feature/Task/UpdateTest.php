<?php

namespace Tests\Feature\Task;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    private const ROTA = 'task.update';
    private const INVALID_ID = 0;
    private User $user;
    private Project $project;
    private Task $task;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->project = Project::factory()->create();
        $this->project->users()->attach($this->user->getKey(), ['owner' => true]);
        $this->task = Task::factory()->create(['project_id' => $this->project->getKey()]);
    }

    public function testFailAuth()
    {
        $data = Task::factory()->make();
        $response = $this->putJson(route(self::ROTA,  $this->task->getKey()), $data->toArray());
        $response->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testFailValuesLen()
    {
        $data = [
            'title' => str_pad('', 61, 'A'),
        ];

        $response = $this->actingAs($this->user)->putJson(route(self::ROTA,  $this->task->getKey()), $data);
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
        ];

        $response = $this->actingAs($this->user)->putJson(route(self::ROTA,  $this->task->getKey()), $data);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'title',
                    'description',
                    'status',
                    'deadline'
                ]
            ]);
    }

    public function testFailTaskInvalid()
    {
        $data = Task::factory()->make()->toArray();

        $response = $this->actingAs($this->user)->putJson(route(self::ROTA,  self::INVALID_ID), $data);
        $response->assertStatus(404)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testSucess()
    {
        $data = Task::factory()->make();

        $response = $this->actingAs($this->user)->putJson(route(self::ROTA, $this->task->getKey()), $data->toArray());
        $response->assertStatus(200)
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
