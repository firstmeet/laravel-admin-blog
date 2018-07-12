<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    protected $guarded=[];
    public function setSkuAttribute($sku)
    {
        if (is_array($sku)) {
            $sku=array_filter($sku);
            $arr=[];
            foreach ($sku as $key=>$value){
                $sku_spec=SkuSpec::find($value);
                $str=$sku_spec->sku_spec_group_id.':'.$value;
                array_push($arr,$str);
            }
            $this->attributes['sku'] = json_encode($arr);
        }
    }
}
