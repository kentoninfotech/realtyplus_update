<?php

namespace Database\Factories;

use App\Models\PropertyTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Client;
use App\Models\Owner;
use App\Models\Business;
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

        $payerTypes = [
            Tenant::inRandomOrder()->first() ?? Tenant::factory()->create(),
            Client::inRandomOrder()->first() ?? Client::factory()->create(),
            Owner::inRandomOrder()->first() ?? Owner::factory()->create(),
        ];
        $payer = $this->faker->randomElement($payerTypes);

        $business = Business::inRandomOrder()->first() ?? Business::factory()->create();

        return [
            'business_id'           => $business->id,
            'transactionable_type' => $transactionableType,
            'transactionable_id' => $transactionableId,
            'type'                  => $this->faker->randomElement(['credit', 'debit']),
            'payer_type'            => get_class($payer),
            'payer_id'              => $payer->id,
            'amount'                => $this->faker->randomFloat(2, 10000, 1000000),
            'purpose'               => $this->faker->randomElement(['full_payment', 'partial_payment', 'refund', 'maintenance_expense', 'lease_payment']),
            'transaction_date'      => $this->faker->date(),
            'description'           => $this->faker->optional()->sentence,
            'status'                => $this->faker->randomElement(['completed', 'pending', 'failed', 'reversed']),
            'payment_method'        => $this->faker->optional()->randomElement(['Bank Transfer', 'Cash', 'Credit Card', 'Cheque']),
            'reference_number'      => strtoupper($this->faker->unique()->bothify('TXN#######')),
        ];
    }
}
