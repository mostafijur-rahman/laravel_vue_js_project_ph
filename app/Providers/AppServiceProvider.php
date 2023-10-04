<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        Relation::morphMap([
            
            'expense_generals'=>'App\Models\Expenses\General',
            'expense_oils'=>'App\Models\Expenses\Oil',
            
            'account_trans'=>'App\Models\Accounts\AccountTransection',
            'loan_trans'=>'App\Models\Loans\LoanTransection',
            
            'vendors'=>'App\Models\Vendors\Vendor',
            'vendor_payments'=>'App\Models\Vendors\VendorPayment',

            'clients'=>'App\Models\Clients\Client',
            'client_payments'=>'App\Models\Clients\ClientPayment',
            
            'challans'=>'App\Models\Challans\Challan',

        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
