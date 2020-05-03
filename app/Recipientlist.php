<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipientlist extends Model
{
    protected $fillable = [
        'user_id','name', 'father', 'age','weight','mobile','address','roger_bornona','doctor_type','gender','status',
    ];
}
