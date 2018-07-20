<?php

namespace App\Http\Controllers\api;

use App\Repositories\AuthUserInterface;
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
    protected $auth;
    public function __construct(GroupBuyingSubInterface $group_sub,GroupBuyingService $group_service,AuthUserInterface $auth)
    {
        $this->config=config('pay.wechat');
        $this->group_sub=$group_sub;
        $this->group_service=$group_service;
        $this->auth=$auth;
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
        $group_sub=$this->group_sub->where([['order_id','=',$request->get('order_id')]])->findByWhere();
        if (!$group_sub){
            return response()->json("订单不存在",403);
        }
        $user=Auth::user();
        $mp=$this->auth->where([['identify_type','=','mp'],['user_id','=',$user->id]])->findByWhere();
        $miniapp=$this->auth->where([['identify_type','=','miniapp'],['user_id','=',$user->id]])->findByWhere();
        $order=[
            'out_trade_no'=>$request->get('order_id'),
            'total_fee'=>$group_sub->payment_amount,
            'body'=>'拼团:'.$request->get('order_id'),
            'spbill_create_ip' => $request->getClientIp()??'127.0.0.1',
            'openid'=>$mp->identifier
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
                $order['openid']=$miniapp->identifier;
                return Pay::wechat($this->config)->miniapp($order);
        }
    }
    public function notify(Request $request)
    {
        $alipay=Pay::alipay($this->config);
        try{
            $data=$alipay->verify();
            if ($this->group_sub->where([['order_id','=',$data->out_trade_no]])->findByWhere()){
                $result=$this->group_service->createGroupToOrder($data->out_trade_no,$data->trade_no,1);
            }

        }catch (Exception $exception){

        }
    }
}
