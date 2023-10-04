<?php

namespace App\Models\Challans;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// for activity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

// model
use App\Models\User;
use App\Models\Staffs\Staff;
use App\Models\Vehicles\Vehicle;


class ChallanOwnVehicle extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'challan_own_vehicles';
    
    public $timestamps = false;
    protected $fillable = [
        'encrypt',
        'challan_id',
        'vehicle_id',
        'driver_id',
        'guide_id',
        'helper_id',
    ];

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function challan(){
        return $this->hasOne(challans::class, 'id', 'challan_id');
    }

    public function vehicle_info(){
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }

    public function driver_info(){
        return $this->hasOne(Staff::class, 'id', 'driver_id');
    }

    public function guide_info(){
        return $this->hasOne(Staff::class, 'id', 'guide_id');
    }

    public function helper_info(){
        return $this->hasOne(Staff::class, 'id', 'helper_id');
    }

    public function created_info(){
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updated_info(){
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }
}

