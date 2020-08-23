<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupermarketBranch extends Model
{
    protected $table = 'supermarket_branch';

    protected $guarded = ['id'];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $fillable = [
        'supermarket_branch_name', 'supermarket_branch_address', 'supermarket_branch_status'
    ];

    public function supermarket()
    {
        return $this->belongsToMany(Supermarket::class, 'supermarket_branch_bridge')->withTimestamps();
    }

}
