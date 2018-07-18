<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class goodsController extends Controller
{
    protected $goods;
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        return response()->json($this->goods->paginate($request->get('item',10)));
    }
    public function edit($id)
    {

    }
    public function show($id)
    {

    }
    public function create(Request $request)
    {

    }
    public function store(Request $request)
    {
        $validate=Validator::make($request->all(),[
            'goods_id'=>'required_without|group_id',
            'group_id'=>'required_without|goods_id',
            'sku_id'=>'required'
        ]);
        if ($validate->fails()){
            return response()->json($validate->errors(),401);
        }
    }
    public function update(Request $request,$id)
    {

    }
    public function destroy($id)
    {

    }
}
