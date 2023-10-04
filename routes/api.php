<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

// setting
use App\Http\Controllers\Settings\CompanySetupController;
use App\Http\Controllers\Settings\BankController;
use App\Http\Controllers\Settings\ExpenseController;
use App\Http\Controllers\Settings\AreaController;

// Client
use App\Http\Controllers\Clients\ClientController;
use App\Http\Controllers\Clients\ClientBillController;
use App\Http\Controllers\Clients\ClientPaymentController;
use App\Http\Controllers\Clients\ClientReportController;

// Vendor
use App\Http\Controllers\Vendors\VendorController;
use App\Http\Controllers\Vendors\VendorReportController;
use App\Http\Controllers\Vendors\VendorBillController;
use App\Http\Controllers\Vendors\VendorPaymentController;

// expense
use App\Http\Controllers\Expenses\GeneralController;
use App\Http\Controllers\Expenses\OilController;

// user
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\RoleController;
use App\Http\Controllers\Users\ActivityController;

// staff
use App\Http\Controllers\Staffs\StaffController;
use App\Http\Controllers\Staffs\StaffReferenceController;

// vehicle
use App\Http\Controllers\Vehicles\VehicleController;

// challans
use App\Http\Controllers\Challans\DepositChallanController;
// use App\Http\Controllers\Challans\ClientChallanController;
// use App\Http\Controllers\Challans\OwnVehicleChallanController;
// use App\Http\Controllers\Challans\ClientChallanTransectionController;
use App\Http\Controllers\Challans\DepositChallanReportController;

// commisssion challan
use App\Http\Controllers\Challans\CommissionChallanController;
use App\Http\Controllers\Challans\CommissionChallanReportController;

// passenger challan
use App\Http\Controllers\Challans\PassengerChallanController;


// accounts
use App\Http\Controllers\Accounts\AccountController;
use App\Http\Controllers\Accounts\CashInCashOutController;
use App\Http\Controllers\Accounts\BalanceTransferController;
use App\Http\Controllers\Accounts\AccountReportController;

// common
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Reports\OtherReportController;

// loans
use App\Http\Controllers\Loans\LoanController;


use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')
//     ->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/auth-user', [UserController::class, 'getAuthUser']);


    // settings
    Route::prefix('settings')->group(function () {

        Route::post('save-company', [CompanySetupController::class, 'saveCompany']);  
        Route::get('get-company', [CompanySetupController::class, 'getCompany']);

        Route::resource('banks', BankController::class);
        Route::resource('expenses', ExpenseController::class);
        Route::resource('areas', AreaController::class);

    });

    // expenses
    Route::prefix('expense')->group(function () {

        Route::resource('generals', GeneralController::class);
        // Route::resource('oils', ExpenseController::class);
        // Route::resource('challans', AreaController::class);

    });

    // Route::prefix('user')->group(function () {
    //     Route::resource('/', UserController::class);
    //     Route::post('/update-password/{id}', [UserController::class, 'updatePassword']);
    //     Route::resource('roles', RoleController::class);
    //     Route::get('activities', [ActivityController::class, 'index']);
    // });
    
    // users route
    Route::resource('/user', UserController::class);
    Route::post('/user-update-password/{id}', [UserController::class, 'updatePassword']);
    Route::resource('/user-roles', RoleController::class);
    Route::get('/user-activities', [ActivityController::class, 'index']);
    
    // staff routes
    Route::resource('/staffs', StaffController::class);
    Route::resource('/staff-references', StaffReferenceController::class);

    // vehicle routes
    Route::resource('/vehicles', VehicleController::class);

    // vendor routes
    Route::resource('/vendors', VendorController::class);
    Route::resource('/vendor-bills', VendorBillController::class);
    Route::resource('/vendor-payments', VendorPaymentController::class);
        
    Route::get('/pump-billing-first-format', [VendorReportController::class, 'pumpBillingFirstFormat']);

    Route::get('/vendor-billing-first-format', [VendorReportController::class, 'vendorBillingFirstFormat']);
    Route::get('/vendor-loan-first-format', [VendorReportController::class, 'vendorLoanFirstFormat']);
    Route::get('/vendor-loan-and-payment-first-format', [VendorReportController::class, 'vendorLoanAndPaymentFirstFormat']);
    Route::get('/vendor-transection-first-format', [VendorReportController::class, 'vendorTransectionFirstFormat']);

    Route::get('/vehicle-supplier-billing-first-format', [VendorReportController::class, 'vehicleSupplierBillingFirstFormat']);
    Route::get('/vehicle-supplier-payment-first-format', [VendorReportController::class, 'vehicleSupplierPaymentFirstFormat']);
    Route::get('/vehicle-supplier-payment-first-format', [VendorReportController::class, 'vehicleSupplierPaymentFirstFormat']);

    // deposit challans
    Route::resource('/deposit-challans', DepositChallanController::class);
    // Route::resource('/client-challans', ClientChallanController::class);
    // Route::resource('/client-challan-transections', ClientChallanTransectionController::class);
    // Route::get('/all-transections-of-a-challan', [ClientChallanTransectionController::class, 'allTransOfAChallan']);

    // report
    Route::get('/deposit-single-challan', [DepositChallanReportController::class, 'singleChallan']);
    Route::get('/deposit-challan-first-format', [DepositChallanReportController::class, 'firstFormat']);
    
    // deposit challans
    Route::resource('/commission-challans', CommissionChallanController::class);
    Route::post('/challan-note', [CommissionChallanController::class, 'challanNote']);
    Route::get('/commission-single-challan', [CommissionChallanReportController::class, 'singleChallan']);
       
    // report
    Route::get('/commission-challan-first-format', [CommissionChallanReportController::class, 'firstFormat']);

    // passenger challans
    Route::resource('/passenger-challans', PassengerChallanController::class);
    

    // accounts routes
    Route::resource('/accounts', AccountController::class);
    Route::get('/account-transactions', [AccountController::class, 'accountTransactions']);
    Route::delete('/account-transaction-destroy/{id}', [AccountController::class, 'transactionDestroy']);
    Route::resource('/cash-in-cash-outs', CashInCashOutController::class);
    Route::resource('/balance-transfers', BalanceTransferController::class);
    // account reports
    Route::get('/account-first-format', [AccountReportController::class, 'accountFirstFormat']);

    // clients routes
    Route::resource('/clients', ClientController::class);
    Route::get('/client-payment-histories', [ClientController::class, 'paymentHistory']);
    Route::resource('/client-bills', ClientBillController::class);
    Route::resource('/client-payments', ClientPaymentController::class);

    // report
    Route::get('/client-billing-first-format', [ClientReportController::class, 'billingFirstFormat']);
    Route::get('/client-payment-first-format', [ClientReportController::class, 'paymentFirstFormat']);
    
    // expenses
    Route::resource('/expense-generals', GeneralController::class);
    Route::resource('/expense-oils', OilController::class);

    // loan
    Route::post('/single-loan-payment-store', [LoanController::class, 'singleLoanPaymentStore']);
    Route::delete('/single-loan-payment-delete/{id}', [LoanController::class, 'singleLoanPaymentDestroy']);

    // Route::get('/loan-payments', [LoanController::class, 'loanPayments']);


    // other report
    Route::prefix('reports')->group(function () {
        Route::get('/nagadan-or-deposit-expense-first-format', [OtherReportController::class, 'nagadanOrDepositExpenseFirstFormat']);
        Route::get('/vehicle-ledger-first-format', [OtherReportController::class, 'vehicleLedgerFirstFormat']);
        Route::get('/expense-ledger-first-format', [OtherReportController::class, 'expenseLedgerFirstFormat']);

    });


    Route::prefix('common')->group(function () {

        // Route::get('settings', [CommonController::class, 'getSettings']);
        Route::get('setting-banks', [CommonController::class, 'getBank']);
        Route::get('setting-designations', [CommonController::class, 'getDesignation']);
        Route::get('setting-areas', [CommonController::class, 'getAreas']);
        Route::get('setting-expenses', [CommonController::class, 'getExpenses']);

        Route::get('staffs', [CommonController::class, 'getStaff']);
        Route::get('vehicles', [CommonController::class, 'getVehicles']);
        Route::get('clients', [CommonController::class, 'getClients']);
        Route::get('vendors', [CommonController::class, 'getVendors']);
        Route::get('accounts', [CommonController::class, 'getAccounts']);
        Route::get('cacheOptimize', [CommonController::class, 'cacheOptimize']);

        
        
        // Route::get('general-expenses', [CommonController::class, 'getGeneralExpenses']);
        
        Route::get('get-challan-client/{challanClientId}', [CommonController::class, 'getChallanClient']);
        // Route::get('get-challan-client-transections', [CommonController::class, 'getChallanClientTransection']);
        Route::post('get-staff-based-on-vehicle', [CommonController::class, 'getStaffBasedOnVehicle']);
        Route::get('verify-unique-challan/{challanNumber}', [CommonController::class, 'verifyUniqueChallan']);
        Route::post('get-vehicles-based-on-vendor', [CommonController::class, 'getVehiclesBasedOnVendor']);
        Route::get('get-unique-challan-number', [CommonController::class, 'getUniqueChallanNumber']);

        

        // Route::get('call/{key}',function($key){
        //     if($key == '3135'){
        //     try{
        //             \Artisan::call('migrate');
        //             echo 'Migrated Successfully!';
        //         }
        //         catch(\Exception $e){
        //             echo $e->getMessage();
        //         }
        //     } else {
        //         echo 'Not matched!';
        //     }
        // });
        


    });

});

Route::get('cacheOptimize', [CommonController::class, 'cacheOptimize']);