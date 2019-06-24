<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['doctor_id', 'service_id', 'customer_id', 'date', 'start', 'end'];
}
