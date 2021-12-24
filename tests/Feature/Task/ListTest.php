<?php

namespace Tests\Feature\Task;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class ListTest extends TestCase
{
    private const ROTA = 'task.list';
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
        $response = $this->postJson(route(self::ROTA));
        $response->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testSucess()
    {

        $response = $this->actingAs($this->user)->postJson(route(self::ROTA));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'status',
                        'deadline',
                        'project_id',
                        'project_name'
                    ]
                ]
            ]);
    }

    public function testSucessFilter()
    {
        $data = [
            'status'     => 'OPEN',
            'deadline'   => now()->format('Y-m-d'),
            'project_id' => $this->project->getKey()
        ];

        $response = $this->actingAs($this->user)->postJson(route(self::ROTA), $data);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'status',
                        'deadline',
                        'project_id',
                        'project_name'
                    ]
                ]
            ]);
    }
}
