<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class WechatPayController extends Controller
{
    protected $config;
    public function __construct()
    {
        $this->config=config('pay.wechat');
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
        switch ($request->get('type')){
            case 'miniapp':
                break;
            case 'wap':
                break;
            case 'app':
                break;
            case 'mp':
                break;
            case 'scan':
                break;
        }
    }
    public function notify(Request $request,$type)
    {

    }
}
