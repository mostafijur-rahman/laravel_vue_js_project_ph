<?php

namespace App\Models\Loans;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// for activity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
;
use App\Models\Accounts\AccountTransection;
use App\Models\User;

class LoanTransection extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'loans_trans';
    protected $primaryKey = 'id';

    protected $fillable = [
                        'encrypt',
                        
                        'providerable_type',
                        'providerable_id',

                        'purposeable_type',
                        'purposeable_id',

                        'type',
                        'amount',
                        'date_time',
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


    public function providerable()
    {
        return $this->morphTo();
    }

    public function purposeable()
    {
        return $this->morphTo();
    }

    public function transactions()
    {
        return $this->morphMany(AccountTransection::class, 'transactionable');
    }

    public function created_info(){
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }

    public function updated_info(){
        return $this->belongsTo(User::class, 'updated_by')->withDefault();
    }

}