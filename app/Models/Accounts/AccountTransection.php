<?php

namespace App\Models\Accounts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// for activity
// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;

use App\Models\Accounts\Account;
use App\Models\User;

class AccountTransection extends Model
{
    // use SoftDeletes, LogsActivity;
    use SoftDeletes;
    
    protected $table = 'account_trans';
    protected $primaryKey = 'id';

    protected $fillable = [
                        'encrypt',
                        'account_id',
                        
                        'transactionable_type',
                        'transactionable_id',
                        'type',
                        'amount',
                        'date_time',
                        'note',

                        'company_id',
                        'created_by',
                        'updated_by',
                    ];

    protected $guarded = [];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()->logOnly(['*']);
    // }

    public function account(){
    	return $this->hasOne(Account::class, 'id', 'account_id');
    }

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function created_by(){
    	return $this->hasOne(User::class, 'id', 'created_by');
    }

}