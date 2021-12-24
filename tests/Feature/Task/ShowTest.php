<?php

namespace Tests\Feature\Task;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class ShowTest extends TestCase
{
    private const ROTA = 'task.show';
    private const INVALID_ID = 0;
    private User $user;
    private Project $project;

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
        $response = $this->getJson(route(self::ROTA, $this->task->getKey()));
        $response->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testFailTaskInvalid()
    {
        $response = $this->actingAs($this->user)->putJson(route(self::ROTA,  self::INVALID_ID));
        $response->assertStatus(404)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testSucess()
    {

        $response = $this->actingAs($this->user)->getJson(route(self::ROTA, $this->task->getKey()));
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
