<?php

namespace App\Models\Settings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// for activity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Models\Expenses\General;

class SettingExpense extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'setting_expenses';

    protected $fillable = [
        'sort',
        'name',
        'type_id',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['sort','name','type_id','status']);
    }

    public function expenses(){
        return $this->hasMany(General::class, 'expense_id', 'id');
    }

    // public function expense(){
    //     return $this->hasOne(General::class, 'expense_id', 'id');
    // }

    // public function expenses(){
    //     return $this->hasMany(General::class, 'expense_id', 'id');
    // }

}