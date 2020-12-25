<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarServices extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'service_charge',
        'repair_hardware_name',
        'repair_hardware_price',
        'garage_name',
        'garage_address',
        'garage_phone_num',
    ];


    public function carDetails()
    {
        return $this->belongsTo('App\Models\CarDetails', 'car_id');
    }

}
