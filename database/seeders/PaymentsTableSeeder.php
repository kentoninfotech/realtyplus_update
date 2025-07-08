<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Client;
use App\Models\Owner;
use App\Models\PropertyTransaction;



class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        if (Lease::count() === 0 || PropertyTransaction::count() === 0 || Tenant::count() === 0 || Client::count() === 0 || Owner::count() === 0) {
            echo "Skipping PaymentsTableSeeder: Not enough leases, transactions, or payer types found.\n";
            return;
        }
        Payment::factory(100)->create();
    }
}
