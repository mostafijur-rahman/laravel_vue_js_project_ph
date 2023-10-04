<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/migrate/{key}',function($key){
    if($key == '3135'){
        try{
            \Artisan::call('migrate');
            echo 'Migrated Successfully!';
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }
    } else {
        echo 'Not matched!';
    }
});

Route::get('/down/{key}',function($key){
    if($key == '3135'){
        try{
            \Artisan::call('down');
            echo 'Service has been down successfully!';
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }
    } else {
        echo 'Not matched!';
    }
});

Route::get('/up/{key}',function($key){
    if($key == '3135'){
        try{
            \Artisan::call('up');
            echo 'Service has been up successfully!';
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }
    } else {
        echo 'Not matched!';
    }
});

Route::get('clear', [CommonController::class, 'cacheOptimize']);

Route::get('/{any}', [DashboardController::class , 'index'])->where('any', '.*');

Auth::routes();