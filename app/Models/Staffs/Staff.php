<?php

namespace App\Models\Staffs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// relation
use App\Models\Settings\SettingDesignation;

// for activity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Staff extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'staffs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'sort',
        'gender',
        'name',
        'phone',
        'email',
        'father_name',
        'mother_name',
        'spouse_name',
        'present_address',
        'permanent_address',
        'dob',
        'blood_group',
        'photo',

        'company_id_number',
        'joining_date',
        'designation_id',
        'nid_number',
        'driving_license_number',
        'passport_number',
        'birth_certificate_number',
        'port_id',
        'bank_id',
        'bank_account_number',
        'salary_amount',
        'termination_date',

        'note',
        'status',

        'created_by',
        'updated_by',
    ];

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly([
            'sort',
            'gender',
            'name',
            'phone',
            'email',
            'father_name',
            'mother_name',
            'spouse_name',
            'present_address',
            'permanent_address',
            'dob',
            'blood_group',
            'photo',
    
            'company_id',
            'joining_date',
            'designation_id',
            'nid_number',
            'driving_license_number',
            'passport_number',
            'birth_certificate_number',
            'port_id',
            'bank_id',
            'bank_account_number',
            'salary_amount',
            'termination_date',
    
            'note',
            'status',
    
            'created_by',
            'updated_by',
        ]);
    }

    public function designation(){
    	return $this->belongsTo(SettingDesignation::class, 'designation_id')->withDefault();
    }





}