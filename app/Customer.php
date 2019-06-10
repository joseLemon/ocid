<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'wp_19bookly_customers';

    public $timestamps = false;
}
