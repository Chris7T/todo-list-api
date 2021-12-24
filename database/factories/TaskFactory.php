<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'title'       => $this->faker->words(3, true),
            'description' => $this->faker->text(100),
            'status'      => 'OPEN',
            'deadline'    => now()->addWeek()->format('Y-m-d'),
            'project_id'  => Project::factory()->create()->getKey()
        ];
    }
}
