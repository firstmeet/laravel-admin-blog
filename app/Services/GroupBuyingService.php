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
    public function getArrSet($arrs, $_current_index = -1)
    {
        //总数组
        static $_total_arr;
        //总数组下标计数
        static $_total_arr_index;
        //输入的数组长度
        static $_total_count;
        //临时拼凑数组
        static $_temp_arr;

        //进入输入数组的第一层，清空静态数组，并初始化输入数组长度
        if ($_current_index < 0) {
            $_total_arr = array();
            $_total_arr_index = 0;
            $_temp_arr = array();
            $_total_count = count($arrs) - 1;
            self::getArrSet($arrs, 0);
        } else {
            //循环第$_current_index层数组
            foreach ($arrs[$_current_index] as $v) {
                //如果当前的循环的数组少于输入数组长度
                if ($_current_index < $_total_count) {
                    //将当前数组循环出的值放入临时数组
                    $_temp_arr[$_current_index] = $v;
                    //继续循环下一个数组
                    self::getArrSet($arrs, $_current_index + 1);

                } //如果当前的循环的数组等于输入数组长度(这个数组就是最后的数组)
                else if ($_current_index == $_total_count) {
                    //将当前数组循环出的值放入临时数组
                    $_temp_arr[$_current_index] = $v;
                    //将临时数组加入总数组
                    $_total_arr[$_total_arr_index] = $_temp_arr;
                    //总数组下标计数+1
                    $_total_arr_index++;
                }

            }
        }
        return $_total_arr;
    }
}