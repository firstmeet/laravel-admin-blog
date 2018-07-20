<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupBuyingSub extends Model
{
    protected $guarded=[];
    protected $appends=['province_str','city_str','county_str'];

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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function group()
    {
        return $this->belongsTo(GroupBuying::class,'group_id','group_id');
    }
}
