<?php

namespace App\Models\Staffs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// for activity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StaffReference extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'staff_references';
    protected $primaryKey = 'id';

    protected $fillable = [
        'sort',
        'staff_id',
        'name',
        'relation',
        'phone',
        'nid_number',
        'address',
        'main_referrer',
        'company_id',
        'created_by',
        'updated_by',
    ];

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly([
            'sort',
            'staff_id',
            'referrer',
            'relation',
            'phone',
            'nid_number',
            'address',
            'main_referrer',
            'created_by',
            'updated_by',
        ]);
    }

}