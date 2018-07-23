<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18
 * Time: 16:55
 */

namespace App\Services;


use App\Address;
use App\Good;
use App\GroupBuying;
use App\GroupBuyingSub;
use App\Order;
use App\OrderDetail;
use App\Sku;
use Faker\Provider\Uuid;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GroupBuyingService
{

    public function create($goods_id=null,$group_id=null,$sku_id,$address_id,$user_id)
    {
        $sku=Sku::where('sku_id',$sku_id)->first();
        $address=Address::find($address_id);
        if ($group_id){
            $group_info=GroupBuying::where('group_id',$group_id)->first();
            if (!$group_info){
             return false;
            }
            $sub_create=[
                'group_id'=>$group_id,
                'stocks_name'=>$sku->sku,
                'sku_id'=>$sku_id,
                'province_id'=>$address->province,
                'city_id'=>$address->city,
                'county_id'=>$address->county,
                'address'=>$address->address,
                'phone_number'=>$address->phone_number,
                'consignee_name'=>$address->consignee_name,
                'payment_amount'=>$sku->active_price,
                'order_id'=>date('YmdHis',time()).rand(1000,9999).$user_id,
                'is_master'=>0,
                'is_pay'=>0,
                'status'=>0,
                'user_id'=>$user_id

            ];
            $sub_result=GroupBuyingSub::create($sub_create);
            if ($sub_result){
                return $sub_result;
            }else{
                return false;
            }

        }else{
            DB::beginTransaction();
            try{
                $goods_info=Good::find($goods_id);
                $group_create=[
                    'group_id'=>Uuid::uuid(),
                    'user_id'=>$user_id,
                    'goods_id'=>$goods_id,
                    'freight_id'=>isset($goods_info['freight_id'])?$goods_info['freight_id']:0,
                    'group_size'=>$goods_info['active_man_number'],
                    'current_size'=>0,
                    'exp_time'=>Carbon::now()->addHours($goods_info['active_valid_hours'])->toDateTimeString()
                ];
                $result=GroupBuying::create($group_create);
                $sub_create=[
                    'group_id'=>$result->group_id,
                    'stocks_name'=>$sku->sku,
                    'sku_id'=>$sku_id,
                    'province_id'=>$address->province,
                    'city_id'=>$address->city,
                    'county_id'=>$address->county,
                    'address'=>$address->address,
                    'phone_number'=>$address->phone_number,
                    'consignee_name'=>$address->consignee_name,
                    'payment_amount'=>$sku->group_price,
                    'order_id'=>date('YmdHis',time()).rand(1000,9999).$user_id,
                    'is_master'=>1,
                    'is_pay'=>0,
                    'status'=>0,
                    'user_id'=>$user_id
                ];
                $sub_result=GroupBuyingSub::create($sub_create);
                DB::commit();
                return $sub_result;
            }catch (\Exception $exception){
                DB::rollBack();
                Log::info("拼团错误:".$exception->getMessage());
                return false;
            }


        }
    }
    public function createGroupToOrder($order_id,$trade_no,$payment_type)
    {
        $group_sub=GroupBuyingSub::with(['group'=>function($query){
            $query->with('goods');
        }])->where('order_id',$order_id)->first();
        $sku=Sku::where('sku_id','=',$group_sub->sku_id)->first();
        $order_data=[
            'order_id'=>$order_id,
            'user_id'=>$group_sub->user_id,
            'trade_no'=>$trade_no,
            'type'=>1,
            'province_id'=>$group_sub->province_id,
            'city_id'=>$group_sub->city_id,
            'county_id'=>$group_sub->county_id,
            'address'=>$group_sub->address,
            'consignee_name'=>$group_sub->consignee_namme,
            'phone_number'=>$group_sub->phone_number,
            'payment_amount'=>$group_sub->payment_amount,
            'pay_time'=>Carbon::now()->toDateTimeString(),
            'is_pay'=>1,
            'payment_type'=>$payment_type
        ];
        $order_result=Order::create($order_data);
        $detail_data=[
            'order_id'=>$order_id,
            'goods_name'=>$group_sub->group->goods->goods_name,
            'sku_id'=>$group_sub->sku_id,
            'goods_id'=>$group_sub->group->goods_id,
            'sku_name'=>$sku->sku,
            'price'=>$group_sub->is_master?$sku->group_price:$sku->active_price,
        ];
        $detail_result=OrderDetail::create($detail_data);
        Log::info('团购写入订单:'.$order_id);
    }
}