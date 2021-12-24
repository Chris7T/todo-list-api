<?php

namespace Tests\Feature\Project;

use App\Models\Project;
use App\Models\User;
use Tests\TestCase;

class ShowTest extends TestCase
{
    private const ROTA = 'project.show';
    private const INVALID_ID = 0;
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
        $response = $this->getJson(route(self::ROTA, $this->project->getKey()));
        $response->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testFailInvalidProject()
    {
        $response = $this->actingAs($this->user)->getJson(route(self::ROTA, self::INVALID_ID));
        $response->assertStatus(404)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testSucess()
    {
        $response = $this->actingAs($this->user)->getJson(route(self::ROTA, $this->project->getKey()));
        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' =>
                    [
                        'id',
                        'title',
                        'owner'
                    ]
                ]
            );
    }
}
