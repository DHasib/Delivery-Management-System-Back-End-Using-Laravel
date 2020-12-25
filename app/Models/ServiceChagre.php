<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceChagre extends Model
{
    use HasFactory;

    protected $fillable = [
        'charge_amount',
        'discount'
    ];

    public function profile()
    {
        return $this->hasOne('App\Models\Profile','service_charge_id');
    }

}
