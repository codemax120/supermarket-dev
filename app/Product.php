<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $guarded = ['id'];

    protected $fillable = [
        'product_name', 'product_price', 'product_price', 'product_image', 'product_due_date', 'product_weight', 'product_perishable'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

}
