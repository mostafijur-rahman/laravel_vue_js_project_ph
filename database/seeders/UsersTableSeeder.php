<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create some roles 
        $admin = Role::create(['name' => 'Admin', 'company_id' => 1]); // 'guard_name' => 'sanctum'
        $owner = Role::create(['name' => 'Owner', 'company_id' => 1]);
        $shareholder = Role::create(['name' => 'Share holder', 'company_id' => 1]); 
        $manager = Role::create(['name' => 'Manager', 'company_id' => 1]);
        $operator = Role::create(['name' => 'Operator', 'company_id' => 1]);

        // create admin user
        $user = User::create([
            'first_name' => 'Admin',
            'phone' => '01714078743',
            'password' => bcrypt('313583'),
            'status' => 'active',
            'created_by' => 1,
            'company_id' => 1,
        ]);

        // assign role
        $user->assignRole($admin);
        
    }
}
