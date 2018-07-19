<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Repositories\GoodsInterface;
use App\Repositories\GroupBuyingInterface;
use App\Repositories\GroupBuyingSubInterface;
use App\Services\GroupBuyingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupBuyingController extends Controller
{
    protected $group;
    protected $group_sub;
    protected $goods;
    protected $service;
    public function __construct(GroupBuyingInterface $group,GroupBuyingService $service,GroupBuyingSubInterface $group_sub,GoodsInterface $goods)
    {
        $this->group=$group;
        $this->group_sub=$group_sub;
        $this->goods=$goods;
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
            return response()->json($validate->errors(),403);
        }

        if ($request->get('group_id')){
            $where=[
                ['user_id','=',Auth::user()->id],
                ['sku_id','=',$request->get('sku_id')]
            ];
            $find_group_sub=$this->group_sub->where($where)->findByWhere();
            if ($find_group_sub){
                return response()->json("您已参加此次拼团,请勿重复拼团",403);
            }
        }else{
            $where=[
                ['user_id','=',Auth::user()->id],
                ['goods_id','=',$request->get('goods_id')]
            ];
            $find_group=$this->group->where($where)->findByWhere();
            if ($find_group){
                return response()->json("您已参加此次拼团,请勿重复拼团",403);
            }

        }

        $result=$this->service->create($request->get('goods_id'),$request->get('group_id'),$request->get('sku_id'),$request->get('address_id'),Auth::user()->id);
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
