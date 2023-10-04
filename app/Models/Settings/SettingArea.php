<?php

namespace App\Models\Settings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// for activity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SettingArea extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'setting_areas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'sort',
        'name',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['sort','name','status']);
    }

}