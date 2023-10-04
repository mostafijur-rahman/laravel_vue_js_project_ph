<?php

namespace App\Models\Expenses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// for activity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Models\Settings\SettingExpense;
use App\Models\Accounts\AccountTransection;
use App\Models\Vendors\Vendor;
use App\Models\Vehicles\Vehicle;
use App\Models\Challans\Challan;
use App\Models\User;
use App\Models\Loans\LoanTransection;


class General extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'expense_generals';

    protected $fillable = [
        'encrypt',
        'vendor_id',
        'vehicle_id',
        'challan_id',
        'expense_id',
        'voucher_number',
        'amount',
        'date_time',
        'note',
        'company_id',
        'created_by',
        'updated_by',
    ];

    protected $guarded = [];

    // protected $appends = ['expense_name'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function expense_info(){
        return $this->hasOne(SettingExpense::class, 'id', 'expense_id');
    } 

    public function transaction()
    {
        return $this->morphOne(AccountTransection::class, 'transactionable');
    }

    public function vendor_info(){
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }

    public function vehicle_info(){
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    } 

    public function challan_info(){
        return $this->hasOne(Challan::class, 'id', 'challan_id');
    } 

    public function created_info(){
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updated_info(){
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

    public function loan()
    {
        return $this->morphOne(LoanTransection::class, 'purposeable');
    }

    protected function getExpenseNameAttribute()
    {
        return $this->expense_info->name;
    }

    

}