<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Accounts\Account;


class AccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // $data = Account::query()->truncate();

        $data = new Account;
        $data->encrypt = uniqid();
        $data->sort = 1;
        $data->type = 'cash';
        $data->bank_id = null;
        $data->holder_name = 'পেটি ক্যাশ';
        $data->account_number = '';     
        $data->user_name = 'পেটি ক্যাশ';   
        $data->balance = 100000;
        $data->status = 'active';
        $data->company_id = 1;
        $data->created_by = 1;
        $data->save();


        $data = new Account;
        $data->encrypt = uniqid();
        $data->sort = 2;
        $data->type = 'bank';
        $data->bank_id = 2;
        $data->holder_name = 'মোস্তাফিজুর রহমান';
        $data->account_number = 3576;     
        $data->user_name = 'শরীফ';   
        $data->balance = 100000;
        $data->status = 'active';
        $data->company_id = 1;
        $data->created_by = 1;
        $data->save();
    
    }
}
