<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected $guarded=[];
    public function setPicturesAttribute($pictures)
    {
        if (is_array($pictures)) {
            $this->attributes['pictures'] = json_encode($pictures);
        }
    }
    public function setSpecGroupsAttribute($spec_groups)
    {
        if (is_array($spec_groups)) {
            $this->attributes['spec_groups'] = json_encode($spec_groups);
        }
    }
    public function getSpecGroupsAttribute($spec_groups)
    {
        return json_decode($spec_groups, true);
    }

    public function getPicturesAttribute($pictures)
    {
        return json_decode($pictures, true);
    }
}
