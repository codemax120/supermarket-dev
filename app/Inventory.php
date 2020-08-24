<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventories';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id', 'product_id', 'product_count', 'month', 'month_order'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

}
