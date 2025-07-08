<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Lease;
use App\Models\PropertyTransaction;
use App\Models\Tenant;
use App\Models\Client;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        $lease = Lease::inRandomOrder()->first() ?? Lease::factory()->create();
        $transaction = PropertyTransaction::inRandomOrder()->first() ?? PropertyTransaction::factory()->create();

        $payerTypes = [
            Tenant::inRandomOrder()->first() ?? Tenant::factory()->create(),
            Client::inRandomOrder()->first() ?? Client::factory()->create(),
            Owner::inRandomOrder()->first() ?? Owner::factory()->create(),
        ];
        $payer = $this->faker->randomElement($payerTypes);

        return [
            'business_id'      => null, // Set in seeder if needed
            'lease_id'         => $lease->id, // Link to a lease
            'transaction_id'   => $transaction->id, // Link to a general transaction
            'payer_type'       => get_class($payer),
            'payer_id'         => $payer->id,
            'amount'           => $this->faker->randomFloat(2, 50000, 1000000),
            'payment_date'     => $this->faker->dateTimeBetween('-1 year', 'now'),
            'payment_method'   => $this->faker->randomElement(['Bank Transfer', 'Cash', 'Credit Card', 'Cheque']),
            'status'           => $this->faker->randomElement(['paid', 'pending', 'failed', 'refunded']),
            'notes'            => $this->faker->optional()->sentence,
        ];
    }
}