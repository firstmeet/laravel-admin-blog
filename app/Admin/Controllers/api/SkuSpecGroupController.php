<?php

namespace App\Admin\Controllers\api;


use App\Http\Controllers\Controller;
use App\SkuSpecGroup;

class SkuSpecGroupController extends Controller
{
    public function index()
    {
        return SkuSpecGroup::where('status',2)->get(['id','name as text']);
    }
}
