<?php

namespace App\Http\Controllers\api;

use App\Repositories\GroupBuyingSubInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Yansongda\Pay\Exceptions\Exception;
use Yansongda\Pay\Pay;

class AliPayController extends Controller
{
    protected $config;
    protected $group_sub;
    public function __construct(GroupBuyingSubInterface $group_sub)
    {
        $this->config=config('pay.alipay');
        $this->group_sub=$group_sub;
    }

    public function index(Request $request)
    {
        $validate=Validator::make($request->all(),[
            'order_id'=>'required',
            'type'=>'required'
        ]);

        if ($validate->fails()){
            return response()->json($validate->errors(),403);
        }
        $group_sub=$this->group_sub->where(['order_id','=',$request->get('order_id')])->findByWhere();
        if (!$group_sub){
            return response()->json("订单不存在",403);
        }
        $order=[
            'out_trade_no'=>$request->get('order_id'),
            'total_amount'=>$group_sub->payment_amount,
            'subject'=>'拼团:'.$request->get('order_id')
        ];
      switch ($request->get('type')){
          case 'web':
              return Pay::alipay($this->config)->web($order);
              break;
          case 'wap':
              return Pay::alipay($this->config)->wap($order);
              break;
          case 'app':
              return Pay::alipay($this->config)->app($order);
              break;
      }
    }
    public function notify(Request $request)
    {
       $alipay=Pay::alipay($this->config);
       try{

       }catch (Exception $exception){

       }
    }
}
