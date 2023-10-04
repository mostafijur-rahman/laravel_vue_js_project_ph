<?php

namespace App\Models\Vendors;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// for activity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Models\Accounts\AccountTransection;

class VendorBill extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'vendor_bills';

    protected $fillable = ['encrypt',

                            'purposeable_type',
                            'purposeable_id',

                            'vendor_id',
                            'bill_type',
                            'voucher_number',
                            'date_time',
                            'amount',
                            'note',

                            'company_id',
                            'created_by',
                            'updated_by',
                        ];

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function vendor_info(){
        return $this->hasOne(Vendor::class, 'id', 'vendor_id'); // foriegn key using in present table
    }

    public function purposeable()
    {
        return $this->morphTo();
    }

    public function transaction()
    {
        return $this->morphOne(AccountTransection::class, 'transactionable');
    }

    public function created_info(){
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updated_info(){
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

}