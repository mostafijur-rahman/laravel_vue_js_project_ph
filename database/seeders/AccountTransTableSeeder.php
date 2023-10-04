<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Accounts\AccountTransection;
use Carbon\Carbon;

class AccountTransTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $data = AccountTransection::query()->truncate();
        // $todayDate = Carbon::now()->format('d/m/Y');
        $now = Carbon::now()->toDateTimeString();
        

        $data = new AccountTransection;
        $data->encrypt = uniqid();
        $data->account_id = 1;
        $data->type = 'in';
        $data->amount = 100000;
        $data->date_time = $now;
        $data->company_id = 1;
        $data->created_by = 1;
        $data->save();


        $data = new AccountTransection;
        $data->encrypt = uniqid();
        $data->account_id = 2;
        $data->type = 'in';
        $data->amount = 100000;
        $data->date_time = $now;
        $data->company_id = 1;
        $data->created_by = 1;
        $data->save();

    }
}
