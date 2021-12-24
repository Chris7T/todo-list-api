<?php

namespace Tests\Feature\Project;

use App\Models\Project;
use App\Models\User;
use Tests\TestCase;

class GenerateLinkTest extends TestCase
{
    private const ROTA = 'project.generate';
    private const INVALID_ID = 0;
    private User $user;
    private Project $project;
    private Project $projectNoPermission;
    private Project $projectWithoutUser;


    public function setUp(): void
    {
        parent::setUp();
        $this->user    = User::factory()->create();
        $this->project = Project::factory()->create();
        $this->projectNoPermission = Project::factory()->create();
        $this->projectWithoutUser = Project::factory()->create();
        $this->project->users()->attach($this->user->getKey(), ['owner' => true]);
        $this->projectNoPermission->users()->attach($this->user->getKey(), ['owner' => false]);
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

    public function testFailNotOwner()
    {

        $response = $this->actingAs($this->user)->getJson(route(self::ROTA, $this->projectNoPermission->getKey()));
        $response->assertStatus(403)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function testFailNotBelongProject()
    {

        $response = $this->actingAs($this->user)->getJson(route(self::ROTA, $this->projectWithoutUser->getKey()));
        $response->assertStatus(404)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function testSucess()
    {
        $response = $this->actingAs($this->user)->getJson(route(self::ROTA, $this->project->getKey()));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);
    }
}
