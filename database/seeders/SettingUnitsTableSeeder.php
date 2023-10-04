<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Settings\SettingUnit;

class SettingUnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [
            ['name' => 'ton'],
            ['name' => 'mon'],
            ['name' => 'kg'],
            ['name' => 'safety']
        ];

        SettingUnit::insert($rows);
    }
}