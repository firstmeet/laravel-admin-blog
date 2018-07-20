<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $guarded=[];

    public function goods()
    {
        return $this->belongsTo(Good::class);
    }
}
