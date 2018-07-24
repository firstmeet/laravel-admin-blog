<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    protected $guarded=[];
    protected $appends=['sku_change'];
    protected $casts = [
        'sku' => 'json',
    ];
    public function getSkuChangeAttribute()
    {
        $arr=$this->sku;
        $arr2=[];
        foreach ($arr as $key=>$value){
            $spec_group=SkuSpecGroup::find($key);
            $spec=SkuSpec::find($value);
            $str=$spec_group->name.':'.$spec->name;
            array_push($arr2,$str);
        }
        return implode(';',$arr2);
    }

}
