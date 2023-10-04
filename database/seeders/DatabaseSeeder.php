<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class); 
        
        // setting
        $this->call(SettingTableSeeder::class); 

        $this->call(SettingAreasTableSeeder::class); 
        $this->call(SettingBanksTableSeeder::class); 
        $this->call(SettingExpensesTableSeeder::class); 
        $this->call(SettingDesignationsTableSeeder::class); 
        $this->call(SettingUnitsTableSeeder::class);

        $this->call(VehicleTableSeeder::class); 
        $this->call(StaffTableSeeder::class); 

        $this->call(AccountTableSeeder::class); 
        $this->call(AccountTransTableSeeder::class); 

        $this->call(ClientTableSeeder::class); 
        $this->call(VendorTableSeeder::class); 

    }
}
