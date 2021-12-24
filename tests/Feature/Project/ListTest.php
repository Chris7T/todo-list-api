<?php

namespace Tests\Feature\Project;

use App\Models\Project;
use App\Models\User;
use Tests\TestCase;

class ListTest extends TestCase
{
    private const ROTA = 'project.index';
    private User $user;
    private Project $project;

    public function setUp(): void
    {
        parent::setUp();
        $this->user    = User::factory()->create();
        $this->project = Project::factory()->create();
        $this->project->users()->attach($this->user->getKey(), ['owner' => true]);
    }

    public function testFailAuth()
    {
        $response = $this->getJson(route(self::ROTA));
        $response->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testSucess()
    {
        $response = $this->actingAs($this->user)->getJson(route(self::ROTA));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'owner'
                    ]
                ]
            ]);
    }
}
