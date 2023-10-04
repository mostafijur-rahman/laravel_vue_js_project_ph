<?php

namespace App\Models\Vendors;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// for activity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Models\Expenses\General;
use App\Models\Expenses\Oil;
use App\Models\Loans\LoanTransection;
use App\Models\Vendors\VendorBill;
use App\Models\Vendors\VendorPayment;
use App\Models\Challans\ChallanRentalVehicle;


class Vendor extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'vendors';
    protected $primaryKey = 'id';

    protected $fillable = [
                            'encrypt',
                            'sort',
                            'name',
                            'phone',
                            'address',
                            'note',
                            'status',

                            'previous_loan',
                            'loan_date',

                            'previous_advance',
                            'advance_date',

                            'company_id',
                            'created_by',
                            'updated_by',
                        ];

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function general_expenses()
    {
        return $this->hasMany(General::class, 'vendor_id', 'id'); // ->withDefault()
    }

    public function oil_expenses()
    {
        return $this->hasMany(Oil::class, 'vendor_id', 'id'); // ->withDefault()
    }

    public function loans()
    {
        return $this->morphMany(LoanTransection::class, 'providerable');
    }

    public function vendor_bills()
    {
        return $this->hasMany(VendorBill::class, 'vendor_id', 'id');
    }

    public function vendor_payments()
    {
        return $this->hasMany(VendorPayment::class, 'vendor_id', 'id');
    }
    
    public function challan_rental_vehicles()
    {
        return $this->hasMany(ChallanRentalVehicle::class, 'vendor_id', 'id');
    }

    



}