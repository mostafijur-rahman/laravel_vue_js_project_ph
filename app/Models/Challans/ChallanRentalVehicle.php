<?php

namespace App\Models\Challans;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// for activity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

// model
use App\Models\User;
use App\Models\Vendors\Vendor;


class ChallanRentalVehicle extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'challan_rental_vehicles';
    
    public $timestamps = false;

    // protected $fillable = [];

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function vendor(){
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }

    public function created_info(){
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updated_info(){
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }
}

