<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Property;
use App\Models\Lease;
use App\Models\Client;
use App\Models\Business;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $documentables = [
            Property::inRandomOrder()->first() ?? Property::factory()->create(),
            Lease::inRandomOrder()->first() ?? Lease::factory()->create(),
            Client::inRandomOrder()->first() ?? Client::factory()->create(),
            User::inRandomOrder()->first() ?? User::factory()->create(),
        ];

        $documentable = $this->faker->randomElement($documentables);
        $business = Business::inRandomOrder()->first() ?? Business::factory()->create();

        return [
            'business_id'         => $business->id,
            'documentable_type' => get_class($documentable),
            'documentable_id' => $documentable->id,
            'title' => $this->faker->words(3, true) . ' Document',
            'file_path' => 'documents/' . $this->faker->uuid() . '.pdf', // Simulate file path
            'file_type' => $this->faker->randomElement(['pdf', 'docx', 'xlsx', 'jpg', 'png']),
            'description' => $this->faker->sentence(),
            'uploaded_by_user_id' => User::inRandomOrder()->first() ?? User::factory()->create(),
        ];
    }
}
