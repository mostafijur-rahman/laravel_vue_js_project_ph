<?php

namespace App\Models\Challans;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// for activity
// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;

use App\Models\Challans\ChallanOwnVehicle;

use App\Models\Settings\SettingArea;

use App\Models\Expenses\General;
use App\Models\Expenses\Oil;
use App\Models\Settings\SettingUnit;
use App\Models\User;

// vendors
use App\Models\Vendors\VendorBill;
use App\Models\Vendors\VendorPayment;

// client
use App\Models\Clients\ClientBill;
use App\Models\Clients\ClientPayment;

class Challan extends Model
{
    use SoftDeletes; // LogsActivity
    
    protected $table = 'challans';

    protected $fillable = [
        'encrypt',
        'type',
        'challan_number',
        'coach_number',

        'start_date_time',
        'destination_reach_date_time',
        'destination_reach_date_time',
        'account_take_date_time',

        'box',
        'weight',
        'unit_id',
        'goods',
        'buyer_name',
        'buyer_code',

        'order_no',
        'depu_change_bill',
        'gate_pass_no',
        'lock_no',
        'load_point_reach_time',
        'note',
        
        'company_id',
        'created_by',
        'updated_by'
    ];

    protected $guarded = [];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()->logOnly(['sort','name','status']);
    // }

    public function points(){
        return $this->belongsToMany(SettingArea::class, 'setting_area_challan','challan_id','setting_area_id')->withPivot('point');
    }

    public function own_vehicle(){
        return $this->hasOne(ChallanOwnVehicle::class, 'challan_id'); // foriegn key using in present table
    }

    // public function client(){
    //     return $this->hasOne(ChallanClient::class, 'challan_id'); // foriegn key using in present table
    // }

    public function general_expenses(){
        return $this->hasMany(General::class, 'challan_id'); // foriegn key using in present table
    }

    public function general_expense_sum(){
        return $this->hasOne(General::class, 'challan_id')->selectRaw('expense_generals.challan_id, SUM(expense_generals.amount) as total')->groupBy('expense_generals.challan_id');
    }

    public function oil_expenses(){
        return $this->hasMany(Oil::class, 'challan_id'); // foriegn key using in present table
    }

    public function oil_expense_sum(){
        return $this->hasOne(Oil::class, 'challan_id')->selectRaw('expense_oils.challan_id, SUM(expense_oils.amount) as total')->groupBy('expense_oils.challan_id');
    }

    public function created_info(){
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updated_info(){
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }
    
    public function unit_info(){
        return $this->hasOne(SettingUnit::class, 'id', 'unit_id');
    }

    public function transaction()
    {
        return $this->morphOne(AccountTransection::class, 'transactionable');
    }

    // commission agent related info
    public function rental_vehicle(){
        return $this->hasOne(ChallanRentalVehicle::class, 'challan_id'); // foriegn key using in present table
    }

    // vendor/supplier bills
    public function vendor_bills()
    {
        return $this->morphMany(VendorBill::class, 'purposeable');
    }

    public function vendor_contract_bills(){
        return $this->morphMany(VendorBill::class, 'purposeable')->where('bill_type', 'contract_rent');
    }

    public function vendor_extend_bills(){
        return $this->morphMany(VendorBill::class, 'purposeable')->where('bill_type', 'extend_rent');
    }

    public function vendor_demurrage_bills(){
        return $this->morphMany(VendorBill::class, 'purposeable')->where('bill_type', 'demurrage_rent');
    }

    // vendor/supplier payments
    public function vendor_payments()
    {
        return $this->morphMany(VendorPayment::class, 'purposeable')->whereIn('payment_type', ['advance_rent', 'after_advance_rent']);
    }

    public function vendor_advance_payments()
    {
        return $this->morphMany(VendorPayment::class, 'purposeable')->where('payment_type', 'advance_rent');
    }

    public function vendor_after_advance_payments()
    {
        return $this->morphMany(VendorPayment::class, 'purposeable')->where('payment_type', 'after_advance_rent');
    }

    public function vendor_security_received_money_payments()
    {
        return $this->morphMany(VendorPayment::class, 'purposeable')->where('payment_type', 'received_money');
    }

    public function vendor_security_paid_money_payments()
    {
        return $this->morphMany(VendorPayment::class, 'purposeable')->where('payment_type', 'paid_money');
    }

    public function vendor_demurrage_payments()
    {
        return $this->morphMany(VendorPayment::class, 'purposeable')->where('payment_type', 'demurrage_rent');
    }

    // client bills
    public function client_bills()
    {
        return $this->morphMany(ClientBill::class, 'purposeable');
    }

    public function client_contract_bills(){
        return $this->morphMany(ClientBill::class, 'purposeable')->where('bill_type', 'contract_rent');
    }

    public function client_extend_bills(){
        return $this->morphMany(ClientBill::class, 'purposeable')->where('bill_type', 'extend_rent');
    }

    public function client_demurrage_bills(){
        return $this->morphMany(ClientBill::class, 'purposeable')->where('bill_type', 'demurrage_rent');
    }

    // client payments
    public function client_payments()
    {
        return $this->morphMany(ClientPayment::class, 'purposeable');
    }

    public function client_advance_payments()
    {
        return $this->morphMany(ClientPayment::class, 'purposeable')->where('payment_type', 'advance_rent');
    }

    public function client_after_advance_payments()
    {
        return $this->morphMany(ClientPayment::class, 'purposeable')->where('payment_type', 'after_advance_rent');
    }

    public function client_demurrage_payments()
    {
        return $this->morphMany(ClientPayment::class, 'purposeable')->where('payment_type', 'demurrage_rent');
    }













    // public function rental_vehicle_extend_rents(){
    //     return $this->hasMany(ChallanRentalVehicleBill::class, 'challan_id')->where('bill_type', 'extend_rent');
    // }

    // public function rental_vehicle_demurrage_rents(){
    //     return $this->hasMany(ChallanRentalVehicleBill::class, 'challan_id')->where('bill_type', 'demurrage_rent');
    // }

    // public function vendor_payments(){
    //     return $this->hasMany(ChallanRentalVehicleBill::class, 'challan_id')->where('bill_type', 'demurrage_rent');
    // }















    // public function getTotalOilExpenseAttribute() {
    //     return $this->oil_expenses->sum('amount');
    //     $data['oil_sum'] = $query->sum('TotalOilExpense');
    // }

    // public function vehicle(){
    // 	return $this->belongsTo(Vehicle::class, 'vehicle_id')->withDefault();
    // }

    // public function user(){
    //     return $this->belongsTo(User::class, 'created_by')->withDefault();
    // }

    // public function user_update(){
    //     return $this->belongsTo(User::class, 'updated_by')->withDefault();
    // }

    // public function driver(){
    //     return $this->belongsTo(SettingStaff::class, 'driver_id')->withDefault();
    // }

    // public function company(){
    //     return $this->hasOne(TripCompany::class, 'trip_id')->withDefault();
    // }

    // public function provider(){
    //     return $this->hasOne(TripProvider::class, 'trip_id')->withDefault();
    // }




}