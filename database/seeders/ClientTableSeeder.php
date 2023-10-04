<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Clients\Client;

class ClientTableSeeder extends Seeder
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
                'name' => 'আকিজ গ্রুপ',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
                
            ],
            [
                'encrypt' => uniqid(),
                'sort' => 1,
                'name' => 'বসুন্ধরা গ্রুপ',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'sort' => 2,
                'name' => 'যমুনা গ্রুপ',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
            ],
            [
                'encrypt' => uniqid(),
                'sort' => 3,
                'name' => 'নাম জানা নাই',
                'status' => 'active',
                'company_id' => 1,
                'created_by' => 1,
            ],
        ];

        Client::insert($rows);
    }

}