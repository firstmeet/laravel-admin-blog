<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class AliPayController extends Controller
{
    protected $config;
    public function __construct()
    {
        $this->config=config('pay.alipay');
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
        $order=[
            'out_trade_no'=>$request->get('order_id'),
//            'total_amount'=>
        ];
      switch ($request->get('type')){
          case 'web':
              break;
          case 'wap':
              break;
          case 'app':
              break;
      }
    }
    public function notify(Request $request,$type)
    {

    }
}
