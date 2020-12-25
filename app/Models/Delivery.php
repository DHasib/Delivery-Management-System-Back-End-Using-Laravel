<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'pickup_address',
        'delivery_address',
        'delivery_mobile',
        'pickup_mobile',
        'amount_Collect',
        'reference_Id',
        'status',

        'instraction',
    ];


    public function profile()
    {
        return $this->belongsTo('App\Models\Profile', 'profile_id');
    }
    public function transaction()
    {
        return $this->hasOne('App\Models\Transaction', 'delivery_id');
    }


}
