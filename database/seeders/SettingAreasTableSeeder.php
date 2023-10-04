<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Settings\SettingArea;

class SettingAreasTableSeeder extends Seeder
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
                'name' => 'যাত্রাবাড়ী',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'sort' => 1,
                'name' => 'সাভার',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'sort' => 2,
                'name' => 'ফার্মগেট',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'sort' => 3,
                'name' => 'একে খান গেট',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'sort' => 4,
                'name' => 'অলংকার',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'sort' => 5,
                'name' => 'চট্টগ্রাম বন্দর',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
            ]
        ];

        SettingArea::insert($rows);
    }
}