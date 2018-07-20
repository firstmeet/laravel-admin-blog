<?php

namespace App\Http\Controllers\api;

use App\Repositories\GroupBuyingSubInterface;
use App\Services\GroupBuyingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use Yansongda\Pay\Exceptions\Exception;
use Yansongda\Pay\Pay;

class WechatPayController extends Controller
{
    protected $config;
    protected $group_sub;
    protected $group_service;
    public function __construct(GroupBuyingSubInterface $group_sub,GroupBuyingService $group_service)
    {
        $this->config=config('pay.wechat');
        $this->group_sub=$group_sub;
        $this->group_service=$group_service;
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
        $user=Auth::user();
        $order=[
            'out_trade_no'=>$request->get('order_id'),
            'total_fee'=>$group_sub->payment_amount,
            'body'=>'拼团:'.$request->get('order_id'),
            'spbill_create_ip' => $request->getClientIp()??'127.0.0.1',
            'openid'=>1
        ];
        switch ($request->get('type')){
            case 'mp':
                return Pay::wechat($this->config)->mp($order);
                break;
            case 'wap':
                return Pay::wechat($this->config)->wap($order);
                break;
            case 'app':
                return Pay::wechat($this->config)->app($order);
                break;
            case 'miniapp':
                return Pay::wechat($this->config)->miniapp($order);
        }
    }
    public function notify(Request $request)
    {
        $alipay=Pay::alipay($this->config);
        try{
            $data=$alipay->verify();
            if ($this->group_sub->where(['order_id','=',$data->out_trade_no])->findByWhere()){

            }

        }catch (Exception $exception){

        }
    }
}
