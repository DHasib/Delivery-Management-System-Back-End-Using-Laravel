<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'due_amount',
        'status',
        'service_charge',
        'net_amount',
       
    ];


    public function delivery()
    {
        return $this->belongsTo('App\Models\Delivery', 'delivery_id');
    }

    public function income()
    {
        return $this->hasMany('App\Models\Income', 'transaction_id');
    }
}
