<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupermarketBranchBridge extends Model
{
    protected $table = 'supermarket_branch_bridge';

    protected $fillable = [
        'supermarket_id', 'supermarket_branch_id'
    ];

    public $timestamps = false;
}
