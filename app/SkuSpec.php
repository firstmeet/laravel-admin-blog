<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkuSpec extends Model
{
    protected $fillable=['name','sku_spec_group_id','value'];
}
