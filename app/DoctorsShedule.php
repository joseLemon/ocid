<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoctorsShedule extends Model
{
    public $timestamps  = false;

    protected $fillable = [
        'user_id', 'day', 'start_time', 'end_time'
    ];
}
