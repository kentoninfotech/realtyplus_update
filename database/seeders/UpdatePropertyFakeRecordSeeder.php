<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;

class UpdatePropertyFakeRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $properties = Property::all();
        foreach($properties as $property){
            if ($property->units->count() > 1){
                $property->total_units = $property->units->count();
                $property->has_units = true;
                $property->save();
            }
        }

    }
}
