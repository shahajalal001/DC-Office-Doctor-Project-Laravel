<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supportslist extends Model
{
    //
    protected $fillable = [
        'user_id','password','name', 'podobi', 'mobile','status'
    ];
}
