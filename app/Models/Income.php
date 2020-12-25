<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;


    protected $fillable = [
        'service_charge',
        'transaction_id',
    ];

 
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction', 'transaction_id');
    }

}
