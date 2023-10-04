<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Vendors\Vendor;

class VendorTableSeeder extends Seeder
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
                'sort' => 0,
                'name' => 'বিসমিল্লাহ ফিলিং স্টেশন',
                'phone' => '01714078743',
                'status' => 'active',
                'type' => 'pump',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'sort' => 1,
                'name' => 'গ্লোরী ফিলিং স্টেশন',
                'phone' => '01714078743',
                'status' => 'active',
                'type' => 'pump',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'sort' => 2,
                'name' => 'সিটি ফিলিং স্টেশন',
                'phone' => '01714078743',
                'status' => 'active',
                'type' => 'pump',
                'company_id' => 1,
                'created_by' => 1,
            ],

            [
                'encrypt' => uniqid(),
                'sort' => 3,
                'name' => 'আমিন সার্ভিসিং সেন্টার',
                'phone' => '01714078743',
                'status' => 'active',
                'type' => 'other',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'sort' => 4,
                'name' => 'জামান হার্ডওয়্যার',
                'phone' => '01714078743',
                'status' => 'active',
                'type' => 'other',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'sort' => 5,
                'name' => 'সোনারগাঁও মোটরস',
                'phone' => '01714078743',
                'status' => 'active',
                'type' => 'other',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'sort' => 6,
                'name' => 'নাম জানা নাই',
                'phone' => '01714078743',
                'status' => 'active',
                'type' => 'other',
                'company_id' => 1,
                'created_by' => 1,
            ],

            [
                'encrypt' => uniqid(),
                'sort' => 7,
                'name' => 'শান্তি এন্টারপ্রাইজ',
                'phone' => '01714078743',
                'status' => 'active',
                'type' => 'vehicle_supplier',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'sort' => 7,
                'name' => 'গ্রামীণ বাংলা ট্রান্সপোর্ট এজেন্সি',
                'phone' => '01714078743',
                'status' => 'active',
                'type' => 'vehicle_supplier',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'sort' => 7,
                'name' => 'ঢাকা ক্যারিয়ার্স প্রাইভেট লিমিটেড',
                'phone' => '01714078743',
                'status' => 'active',
                'type' => 'vehicle_supplier',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'sort' => 7,
                'name' => 'নাম জানা নাই',
                'phone' => '01714078743',
                'status' => 'active',
                'type' => 'vehicle_supplier',
                'company_id' => 1,
                'created_by' => 1,
            ],
        ];

        Vendor::insert($rows);
    }

}