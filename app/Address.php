<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $appends=['province_str','city_str','county_str'];

    public function getProvinceStrAttr()
    {
        return City::where('code',$this->province)->first()['name'];
    }
    public function getCityStrAttr()
    {
        return City::where('code',$this->city)->first()['name'];
    }
    public function getCountyStrAttr()
    {
        return City::where('code',$this->county)->first()['name'];
    }
}
