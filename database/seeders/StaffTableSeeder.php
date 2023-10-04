<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Staffs\Staff;

class StaffTableSeeder extends Seeder
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
                'gender' => 'male',
                'name' => 'দেলোয়ার হোসাইন',
                'phone' => '01714078743',
                'status' => 'active',
                'designation_id' => 1,
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'gender' => 'male',
                'name' => 'জাহাঙ্গীর আলম',
                'phone' => '01714078743',
                'status' => 'active',
                'designation_id' => 1,
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'gender' => 'male',
                'name' => 'সেলিম হোসেন',
                'phone' => '01714078743',
                'status' => 'active',
                'designation_id' => 1,
                'company_id' => 1,
                'created_by' => 1,
            ],

            [
                'encrypt' => uniqid(),
                'gender' => 'male',
                'name' => 'আলম',
                'phone' => '01714078743',
                'status' => 'active',
                'designation_id' => 2,
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'gender' => 'male',
                'name' => 'কবির',
                'phone' => '01714078743',
                'status' => 'active',
                'designation_id' => 2,
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'gender' => 'male',
                'name' => 'সোহান',
                'phone' => '01714078743',
                'status' => 'active',
                'designation_id' => 2,
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'gender' => 'male',
                'name' => 'সাব্বির',
                'phone' => '01714078743',
                'status' => 'active',
                'designation_id' => 4,
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'gender' => 'male',
                'name' => 'রনি',
                'phone' => '01714078743',
                'status' => 'active',
                'designation_id' => 4,
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'gender' => 'male',
                'name' => 'ওয়াহিদ',
                'phone' => '01714078743',
                'status' => 'active',
                'designation_id' => 4,
                'company_id' => 1,
                'created_by' => 1,
            ]
        ];

        Staff::insert($rows);
    }
}