<?php

namespace App\Models\Vehicles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// for activity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Models\Staffs\Staff;

class Vehicle extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'vehicles';
    protected $primaryKey = 'id';

    protected $fillable = [
        'sort',
        'encrypt',
        'ownership',

        'serial',
        'number_plate',
        'registration_number',
        'engine_number',
        'chassis_number',
        'model',
        'manufacturer',

        'body_type',
        'engine_down',
        'fuel_tank_capacity',
        'gps_id',
        'driver_id',
        'guide_id',
        'helper_id',

        'registration_year',
        'tax_token_expire_date',
        'fitness_expire_date',
        'insurance_expire_date',
        'last_tyre_change_date',
        'last_mobil_change_date',
        'last_servicing_date',

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

    public function driver(){
    	return $this->hasOne(Staff::class, 'id', 'driver_id')->withDefault();
    }

    public function helper(){
    	return $this->hasOne(Staff::class, 'id', 'helper_id')->withDefault();
    }

    public function guide(){
    	return $this->hasOne(Staff::class, 'id', 'guide_id')->withDefault();
    }

}