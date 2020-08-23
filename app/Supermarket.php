<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supermarket extends Model
{
    protected $table = 'supermarket';

    protected $guarded = ['supermarket_id'];

    protected $fillable = [
        'supermarket_name', 'supermarket_logo', 'supermarket_status'
    ];

}
