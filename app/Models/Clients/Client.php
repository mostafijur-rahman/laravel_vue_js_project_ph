<?php

namespace App\Models\Clients;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// for activity
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Models\Loans\LoanTransection;

class Client extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected $table = 'clients';
    protected $primaryKey = 'id';

    protected $fillable = [
                            'encrypt',
                            'sort',
                            'name',
                            'phone',
                            'address',
                            'note',
                            'status',

                            'previous_loan',
                            'loan_date',

                            'previous_advance',
                            'advance_date',
                            
                            'company_id',
                            'created_by',
                            'updated_by',
                        ];

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    public function bills(){
        return $this->hasMany(ClientBill::class, 'client_id', 'id');
    }

    public function payments(){
        return $this->hasMany(ClientPayment::class, 'client_id', 'id');
    }

    public function loans()
    {
        return $this->morphMany(LoanTransection::class, 'providerable');
    }


}