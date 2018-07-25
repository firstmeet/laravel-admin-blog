<?php

namespace App\Admin\Controllers;

use App\Good;
use App\Order;

use Carbon\Carbon;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Box;
use function foo\func;

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
            $grid->order_detail("商品")->display(function ($order_detail){
                $res=array_map(function ($detail){
                    $goods=Good::find($detail['goods_id']);
                    return "<tr><td><img src='/uploads/{$goods->picture}' class='img-thumbnail\' alt='Cinque Terre' width='100' height='50'></td><td>{$detail['goods_name']}</td><td>{$detail['sku_name']}</td><td>{$detail['number']}</td><td>{$detail['price']}</td></tr>";
                },$order_detail);
                $join=join('',$res);
                $html="<div class=\"table-responsive\">
	<table class=\"table\">
		<thead>
			<tr style='color: #9f191f'>
			<td>图片</td>
			<td>商品名称</td>
			<td>规格</td>
			<td>数量</td>
			<td>单价</td>
			</tr>
		</thead>
		<tbody>".$join.
                    "</tbody>
</table>
</div>";
                return $html;
//                return join(' ',$res);
            });
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
            $states = [
                '0'  => ['value' => 0, 'text' => '未收货', 'color' => 'primary'],
                '1' => ['value' => 1, 'text' => '已收货', 'color' => 'success'],
            ];
            $grid->is_ship("是否收货")->switch($states);
            $grid->ship_time("收货时间");
//            $grid->is_ship("是否收货")->display(function (){
//                switch ($this->is_ship){
//                    case '0':
//                        return '未收货';
//                        break;
//                    case '1':
//                        return '已收货';
//                        break;
//                }
//            });
            $states = [
                '0'  => ['value' => 0, 'text' => '未发货', 'color' => 'primary'],
                '1' => ['value' => 1, 'text' => '已发货', 'color' => 'success'],
            ];
            $grid->is_receipt("是否发货")->switch($states);
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
            $grid->column("物流信息")->display(function(){
                $html="<ul class=\"list-group\">
	<li class=\"list-group-item\">免费域名注册</li>
	<li class=\"list-group-item\">免费 Window 空间托管</li>
	<li class=\"list-group-item\">图像的数量</li>
	<li class=\"list-group-item\">
		<span class=\"badge\">新</span>
		24*7 支持
	</li>
	<li class=\"list-group-item\">每年更新成本</li>
	<li class=\"list-group-item\">
		<span class=\"badge\">新</span>
		折扣优惠
	</li>
</ul>";
                return $html;
            })->modal("物流信息",'物流信息');
            $grid->column("收货信息")->display(function (){
                $html="<span class='label label-info'>地址:{$this->province_str}{$this->city_str}{$this->county_str}{$this->address}</span><br>
                  <span class='label label-info'>收货人:{$this->consignee_name}</span><br>
                  <span class='label label-info'>联系方式:{$this->phone_number}</span>";
//                $box=new Box('收货信息',$html);
//                echo $box;
                return $html;
            })->popover("left",'收货信息');
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
            $grid->actions(function ($action){
                $action->disableDelete();
                $action->disableEdit();
            });
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
            $grid->filter(function($filter){

                // 去掉默认的id过滤器
                $filter->disableIdFilter();

                // 在这里添加字段过滤器
                $filter->where(function ($query) {

                    $query->where('order_id', '=', "{$this->input}")
                        ->orWhere('phone_number', '=', "{$this->input}");

                }, '请输入订单号或者手机号');
                $filter->between('created_at',"时间范围")->datetime();

            });
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
            $form->switch('is_ship');
            $form->switch('is_receipt');
            $form->display('created_at', 'Created At');
            $form->hidden("ship_number");
            $form->hidden("ship_time");
            $form->hidden("receipt_time");
            $form->saving(function ($form){
//                dd($form);
                if ($form->is_ship=='on'){
                    $form->ship_time=Carbon::now()->toDateTimeString();
                }
                if ($form->is_receipt=='on'){
                    $form->receipt_time=Carbon::now()->toDateTimeString();
                }
            });
            $form->display('updated_at', 'Updated At');
        });
    }
}
