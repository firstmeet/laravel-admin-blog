<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded=[];
    public function order_detail()
    {
        return $this->hasMany(OrderDetail::class,'order_id','order_id');
    }
}
