<?php

namespace Tests\Feature\Project;

use App\Models\Project;
use App\Models\User;
use Tests\TestCase;

class UnlinkTest extends TestCase
{
    private const ROTA = 'project.unlink';
    private const INVALID_PROJECT = 0;
    private User $user;
    private Project $project;

    public function setUp(): void
    {
        parent::setUp();
        $this->user    = User::factory()->create();
        $this->userOut = User::factory()->create();
        $this->userIn  = User::factory()->create();
        $this->project = Project::factory()->create();
        $this->project->users()->attach($this->user->getKey(), ['owner' => true]);
        $this->project->users()->attach($this->userIn->getKey(), ['owner' => false]);
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
        $response = $this->actingAs($this->userIn)->getJson(route(self::ROTA, self::INVALID_PROJECT));
        $response->assertStatus(404)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testFailUserOutProject()
    {
        $response = $this->actingAs($this->userOut)->getJson(route(self::ROTA, $this->project->getKey()));
        $response->assertStatus(409)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testFailUserOwner()
    {
        $response = $this->actingAs($this->user)->getJson(route(self::ROTA, $this->project->getKey()));
        $response->assertStatus(409)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testSucess()
    {
        $response = $this->actingAs($this->userIn)->getJson(route(self::ROTA, $this->project->getKey()));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
