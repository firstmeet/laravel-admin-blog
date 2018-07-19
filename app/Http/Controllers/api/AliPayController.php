<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AliPayController extends Controller
{
    public function index(Request $request)
    {

      switch ($request->get('type')){
          case 'alipay_web':
              break;
          case 'alipay_wap':
              break;
          case 'alipay_app':
              break;
      }
    }
    public function notify(Request $request,$type)
    {

    }
}
