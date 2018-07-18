<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupBuying extends Model
{
    protected $guarded=[];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function goods()
    {
        return $this->belongsTo(Good::class);
    }
}
