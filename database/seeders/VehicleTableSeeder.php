<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Vehicles\Vehicle;

class VehicleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [
            [
                'encrypt' => uniqid(),
                'ownership' => 'own',
                'number_plate' => 'DHK-9891',

                'driver_id' => 1,
                'guide_id' => 7,
                'helper_id' => 4,
                
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'ownership' => 'own',
                'number_plate' => 'DHK-8875',

                'driver_id' => 2,
                'guide_id' => 8,
                'helper_id' => 5,
                
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'ownership' => 'own',
                'number_plate' => 'DHK-5496',

                'driver_id' => 3,
                'guide_id' => 9,
                'helper_id' => 6,
                
                'company_id' => 1,
                'created_by' => 1,
            ],
        ];

        Vehicle::insert($rows);
    }
}