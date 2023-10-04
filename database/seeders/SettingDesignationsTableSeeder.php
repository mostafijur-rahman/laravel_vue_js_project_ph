<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Settings\SettingDesignation;

class SettingDesignationsTableSeeder extends Seeder
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
                'sort' => 0,
                'name' => 'driver',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 1,
                'name' => 'helper',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 2,
                'name' => 'staff',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 3,
                'name' => 'guide',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ]
        ];

        SettingDesignation::insert($rows);
    }
}