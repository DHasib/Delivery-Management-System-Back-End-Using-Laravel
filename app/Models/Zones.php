<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zones extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone_num',
    ];

    public function profile()
    {
        return $this->hasOne('App\Models\Profile', 'zone_id');
    }

}
