<?php

namespace App\Admin\Controllers;

use App\Order;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class OrderController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('订单');
            $content->description('订单列表');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Order::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->order_id("订单号");
            $grid->type("类型")->display(function (){
               switch ($this->type){
                   case '1':
                       return "拼团";
                       break;
                   case '2':
                       return "单独购买";
                       break;
               }
            });
            $grid->order_detail("商品")->pluck('goods_name','sku_name')->implode('-');
            $grid->payment_amount("支付金额");
            $grid->freight("运费");
            $grid->is_pay("是否支付")->display(function (){
                switch ($this->is_pay){
                    case '0':
                        return '未支付';
                        break;
                    case '1':
                        return '已支付';
                        break;
                }
            });
            $grid->pay_time("支付时间");
            $grid->is_ship("是否收货")->display(function (){
                switch ($this->is_ship){
                    case '0':
                        return '未收货';
                        break;
                    case '1':
                        return '已收货';
                        break;
                }
            });
            $grid->ship_time("收货时间");
            $grid->is_ship("是否收货")->display(function (){
                switch ($this->is_ship){
                    case '0':
                        return '未收货';
                        break;
                    case '1':
                        return '已收货';
                        break;
                }
            });
            $grid->is_receipt("是否发货")->display(function (){
                switch ($this->is_receipt){
                    case '0':
                        return '未发货';
                        break;
                    case '1':
                        return '已发货';
                        break;
                }
            });
            $grid->receipt_time("发货时间");
            $grid->ship_number("快递单号")->editable();
            $grid->status("订单状态")->display(function (){
                switch ($this->is_receipt){
                    case '0':
                        return '禁用';
                        break;
                    case '1':
                        return '正常';
                        break;
                    case '-1':
                        return "已删除";
                        break;
                }
            });
            $grid->payment_type("付款方式")->display(function (){
                switch ($this->payment_type){
                    case '1':
                        return '支付宝';
                        break;
                    case '2':
                        return '微信';
                        break;
                }
            });


            $grid->created_at("订单创建时间");
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Order::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
