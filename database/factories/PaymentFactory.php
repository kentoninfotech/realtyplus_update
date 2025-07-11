<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Lease;
use App\Models\PropertyTransaction;
use App\Models\Tenant;
use App\Models\Client;
use App\Models\Owner;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        $lease = Lease::inRandomOrder()->first() ?? Lease::factory()->create();

        // Always use an existing PropertyTransaction, never create a new one here
        $transaction = PropertyTransaction::inRandomOrder()->first();
        if (!$transaction) {
            throw new \Exception('No PropertyTransaction exists. Please seed PropertyTransactions before Payments.');
        }

        $payerTypes = [
            Tenant::inRandomOrder()->first() ?? Tenant::factory()->create(),
            Client::inRandomOrder()->first() ?? Client::factory()->create(),
            Owner::inRandomOrder()->first() ?? Owner::factory()->create(),
        ];
        $payer = $this->faker->randomElement($payerTypes);

        $business = Business::inRandomOrder()->first() ?? Business::factory()->create();

        return [
            'business_id'      => $business->id,
            'lease_id'         => $lease->id, // Link to a lease
            'transaction_id'   => $transaction->id, // Always link to a valid transaction
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