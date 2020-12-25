<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;


    protected $fillable = [
        'salary_amount',
        'bonus'
    ];

    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }


}
