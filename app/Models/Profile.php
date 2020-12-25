<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'about',
        'image',
        'national_id_photo',
        'driving_license_photo',
        'assign_car_id',
        'salary_id',
        'zone_id',
        'service_charge_id',
    ];


    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id' );
    }
    public function assignCar()
    {
        return $this->belongsTo('App\Models\CarDetails', 'assign_car_id');
    }

    public function serviceCharge()
    {
        return $this->belongsTo('App\Models\ServiceChagre','service_charge_id');
    }
    public function employeeSalary()
    {
        return $this->belongsTo('App\Models\Salary', 'salary_id');
    }

    public function zone()
    {
        return $this->belongsTo('App\Models\Zones','zone_id');
    }

    public function delivery()
    {
        return $this->hasMany('App\Models\Delivery','profile_id');
    }

   

}
