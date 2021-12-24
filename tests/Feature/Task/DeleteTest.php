<?php

namespace Tests\Feature\Task;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    private const ROTA = 'task.destroy';
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
        $response = $this->deleteJson(route(self::ROTA, $this->task->getKey()));
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

        $response = $this->actingAs($this->user)->deleteJson(route(self::ROTA, $this->task->getKey()));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
