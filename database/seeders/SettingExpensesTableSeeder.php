<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Settings\SettingExpense;

class SettingExpensesTableSeeder extends Seeder
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
                'name' => 'খোরাকী',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 1,
                'name' => 'ব্রীজ ভাড়া/টোল',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 2,
                'name' => 'লেবার',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 3,
                'name' => 'পুলিশ',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 4,
                'name' => 'ট্রিপ কমিশন',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 5,
                'name' => 'সার্ভিসিং',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 6,
                'name' => 'গ্যাস',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 7,
                'name' => 'ডিজেল',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 8,
                'name' => 'খুচরা পার্টস',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 9,
                'name' => 'মোবিল',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'sort' => 10,
                'name' => 'টায়ার সার্ভিসিং',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
        ];

        SettingExpense::insert($rows);
    }
}