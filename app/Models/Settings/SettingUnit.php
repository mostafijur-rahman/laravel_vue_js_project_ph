<?php

namespace App\Models\Settings;
use Illuminate\Database\Eloquent\Model;

class SettingUnit extends Model
{

    protected $table = 'setting_units';
    protected $fillable = ['name'];

}