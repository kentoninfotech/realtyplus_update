<?php

namespace Database\Factories;

use App\Models\PropertyTask;
use App\Models\User;
use App\Models\Lead;
use App\Models\Property;
use App\Models\Lease;
use App\Models\MaintenanceRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyTaskFactory extends Factory
{
    protected $model = PropertyTask::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $taskables = [
            Lead::inRandomOrder()->first() ?? Lead::factory()->create(),
            Property::inRandomOrder()->first() ?? Property::factory()->create(),
            Lease::inRandomOrder()->first() ?? Lease::factory()->create(),
            MaintenanceRequest::inRandomOrder()->first() ?? MaintenanceRequest::factory()->create(),
        ];

        $taskable = $this->faker->randomElement($taskables);

        return [
            'taskable_type'        => get_class($taskable),
            'taskable_id'          => $taskable->id,
            'title'                => $this->faker->words(4, true) . ' Task',
            'description'          => $this->faker->optional()->paragraph,
            'assigned_to_user_id'  => User::inRandomOrder()->first() ?? User::factory()->create(),
            'due_date'             => $this->faker->optional()->dateTimeBetween('now', '+2 months'),
            'status'               => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'cancelled']),
            'priority'             => $this->faker->randomElement(['low', 'medium', 'high']),
        ];
    }
}
