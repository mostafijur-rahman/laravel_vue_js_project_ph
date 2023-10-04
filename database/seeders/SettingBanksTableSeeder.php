<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Settings\SettingBank;

class SettingBanksTableSeeder extends Seeder
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
                'name' => 'IBBL',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 1,
                'name' => 'BRAC',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 2,
                'name' => 'IFIC',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 3,
                'name' => 'DBBL',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 4,
                'name' => 'bKash',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 5,
                'name' => 'Nagad',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ]
        ];

        SettingBank::insert($rows);
    }
}