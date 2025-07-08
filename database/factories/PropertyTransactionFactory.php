<?php

namespace Database\Factories;

use App\Models\PropertyTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Lease;
use App\Models\Property;

class PropertyTransactionFactory extends Factory
{
    protected $model = PropertyTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $transactionable = $this->faker->randomElement([
            Lease::inRandomOrder()->first() ?? Lease::factory()->create(),
            Property::inRandomOrder()->first() ?? Property::factory()->create(), // For property sales
        ]);

        $transactionableType = $transactionable instanceof Lease ? 'App\\Models\\Lease' : 'App\\Models\\Property';
        $transactionableId = $transactionable->id;

        return [
            'business_id'           => null, // Set in seeder if needed
            'transactionable_type' => $transactionableType,
            'transactionable_id' => $transactionableId,
            'type'                  => $this->faker->randomElement(['payment', 'expense', 'refund']),
            'amount'                => $this->faker->randomFloat(2, 10000, 1000000),
            'transaction_date'      => $this->faker->date(),
            'description'           => $this->faker->optional()->sentence,
            'status'                => $this->faker->randomElement(['completed', 'pending', 'failed', 'cancelled']),
            'payment_method'        => $this->faker->optional()->randomElement(['Bank Transfer', 'Cash', 'Credit Card', 'Cheque']),
            'reference_number'      => strtoupper($this->faker->unique()->bothify('TXN#######')),
        ];
    }
}
