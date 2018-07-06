<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkuSpecGroup extends Model
{
    public function sku_spec()
    {
        return $this->hasMany(SkuSpec::class,'sku_spec_group_id','id');
    }
}
