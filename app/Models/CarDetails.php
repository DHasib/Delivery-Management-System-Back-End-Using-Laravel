<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarDetails extends Model
{
    use HasFactory;


    protected $fillable = [
        'chassis_num',
        'reg_num',
        'model',
        'insurance_image',
        'memo_image',
        'status'
    ];

    public function profile()
    {
        return $this->hasOne('App\Models\Profile', 'assign_car_id');
    }

    public function carServices()
    {
        return $this->hasMany('App\Models\CarServices','car_id');
    }

}
