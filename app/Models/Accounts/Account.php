<?php

namespace App\Models\Accounts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// for activity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Models\Settings\SettingBank;
use App\Models\Accounts\AccountTransection;


class Account extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'accounts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'encrypt',
        'sort',
        'type',
        'bank_id',

        'holder_name',
        'user_name',
        'account_number',
        'balance',

        'note',
        'user_id',
        'status',

        'company_id',
        'created_by',
        'updated_by',
    ];

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function transections(){
    	return $this->hasMany(AccountTransection::class, 'account_id');
    }

    public function bank_info(){
    	return $this->hasOne(SettingBank::class, 'id', 'bank_id');
        // return $this->belongsTo(SettingBank::class, 'bank_id');
    }



}