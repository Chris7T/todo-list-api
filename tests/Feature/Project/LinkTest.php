<?php

namespace Tests\Feature\Project;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class LinkTest extends TestCase
{
    private const ROTA = 'project.link';
    private const INVALID_KEY = 'invalid key';
    private User $user;
    private Project $project;
    private string $key;

    public function setUp(): void
    {
        parent::setUp();
        $this->user    = User::factory()->create();
        $this->newUser = User::factory()->create();
        $this->project = Project::factory()->create();
        $this->project->users()->attach($this->user->getKey(), ['owner' => true]);
        $this->key = Crypt::encryptString($this->project->getKey());
    }

    public function testFailAuth()
    {
        $response = $this->getJson(route(self::ROTA, $this->key));
        $response->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testFailInvalidKey()
    {
        $response = $this->actingAs($this->newUser)->getJson(route(self::ROTA, self::INVALID_KEY));
        $response->assertStatus(500)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testUserAlreadyInProject()
    {
        $this->project->users()->attach($this->newUser->getKey());
        $response = $this->actingAs($this->newUser)->getJson(route(self::ROTA, $this->key));
        $response->assertStatus(409)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testSucess()
    {
        $response = $this->actingAs($this->newUser)->getJson(route(self::ROTA, $this->key));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
