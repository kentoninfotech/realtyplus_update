<?php

namespace Database\Factories;

use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\User;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceRequestFactory extends Factory
{
    protected $model = MaintenanceRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $property = Property::inRandomOrder()->first() ?? Property::factory()->create();
        $propertyUnit = $this->faker->boolean(70) ? (PropertyUnit::where('property_id', $property->id)->inRandomOrder()->first() ?? PropertyUnit::factory()->create(['property_id' => $property->id])) : null;
        $reporter = User::inRandomOrder()->first() ?? User::factory()->create();
        $personnel = Personnel::inRandomOrder()->first() ?? Personnel::factory()->create();

        $reportedAt = $this->faker->dateTimeBetween('-6 months', 'now');
        $completedAt = $this->faker->boolean(60) ? $this->faker->dateTimeBetween($reportedAt, 'now') : null;
        $status = $completedAt ? 'completed' : $this->faker->randomElement(['open', 'in_progress', 'cancelled']);

        return [
            'property_id' => $property->id,
            'property_unit_id' => $propertyUnit ? $propertyUnit->id : null,
            'reported_by_user_id' => $reporter->id,
            'title' => $this->faker->sentence(3) . ' Repair',
            'description' => $this->faker->paragraph(2),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => $status,
            'assigned_to_personnel_id' => $personnel->id,
            'reported_at' => $reportedAt,
            'completed_at' => $completedAt,
        ];
    }
}
