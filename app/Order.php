<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded=[];
    protected $appends=['province_str','city_str','county_str'];
    public function order_detail()
    {
        return $this->hasMany(OrderDetail::class,'order_id','order_id');
    }

    public function getProvinceStrAttribute()
    {
        return City::where('code',$this->province_id)->first()['name'];
    }
    public function getCityStrAttribute()
    {
        return City::where('code',$this->city_id)->first()['name'];
    }
    public function getCountyStrAttribute()
    {
        return City::where('code',$this->county_id)->first()['name'];
    }
}
