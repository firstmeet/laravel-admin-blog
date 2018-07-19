<?php

namespace App\Http\Controllers\api;

use App\Repositories\GroupBuyingInterface;
use App\Services\GroupBuyingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupBuyingController extends Controller
{
    protected $group;
    protected $service;
    public function __construct(GroupBuyingInterface $group,GroupBuyingService $service)
    {
        $this->group=$group;
        $this->service=$service;
    }

    public function index(Request $request)
    {
        return response()->json($this->group->with(['goods'])->paginate($request->get('item',10)));
    }
    public function edit($id)
    {

    }
    public function create(Request $request)
    {

    }
    public function store(Request $request)
    {
        $validate=Validator::make($request->all(),[
            'goods_id'=>'required_without:group_id',
            'group_id'=>'required_without:goods_id',
            'sku_id'=>'required',
            'address_id'=>'required'
        ]);
        if ($validate->fails()){
            return response()->json($validate->errors(),401);
        }
        $result=$this->service->create($request->get('goods_id'),$request->get('group_id'),$request->get('sku_id'),$request->get('address_id'),Auth::user()->id);
        return response()->json($result,200);
        if ($result){
            return response()->json($result,200);
        }else{
            return response()->json('failed',401);
        }

    }
    public function update(Request $request,$id)
    {

    }
    public function destroy($id)
    {

    }

}
